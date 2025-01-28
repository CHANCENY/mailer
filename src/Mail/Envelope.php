<?php

namespace Simp\Mail\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use Simp\Mail\ServerSettings\ServerSettings;

class Envelope
{
   private array $headers;

   private array $params;

   private array $attachments;

   private array $to_addresses;

   private array $cc_addresses;

   private array $bcc_addresses;

   private string $reply_to;

   public function __construct()
   {
       $this->headers = [
           'Content-Type' => 'text/html; charset=UTF-8',
           'Content-Transfer-Encoding' => 'utf-8',
       ];
       $this->params = [
           'subject' => '',
           'body' => '',
       ];
       $this->attachments = [];
       $this->to_addresses = [];
       $this->cc_addresses = [];
       $this->bcc_addresses = [];
       $this->reply_to = '';
   }

   public function addHeader(string $name, string $value): self {
       $this->headers[$name] = $value;
       return $this;
   }

   public function addHeaders(array $headers): self {
       $this->headers = array_merge($this->headers, $headers);
       return $this;
   }

   public function addParam(string $name, string $value): self {
       if($name === 'subject' || $name === 'body') {
           $this->params[$name] = $value;
       }
       return $this;
   }

   public function addParams(array $params): self {
       foreach($params as $name => $value) {
           $this->addParam($name, $value);
       }
       return $this;
   }

   public function addAttachment(string $name, string $content): self {
       $this->attachments[] = [
           'name' => $name,
           'content' => $content,
       ];
       return $this;
   }

   public function addAttachments(array $attachments): self {
       foreach ($attachments as $attachment) {
           if (is_array($attachment)) {
               if (isset($attachment['content']) && isset($attachment['name'])) {
                   $this->addAttachment($attachment['name'], $attachment['content']);
               }
           }
       }
       return $this;
   }

   public function addToAddresses(array $addrs): self {
       foreach ($addrs as $address) {
           if (is_string($address)) {
               $list = explode('@', $address);
               $this->to_addresses[] = [
                   'name' => ucfirst($list[0]),
                   'value' => $address,
               ];
           }
           elseif (is_array($address)) {
               if(isset($address['name']) && isset($address['value'])) {
                   $this->to_addresses[] = $address;
               }
           }
       }
       return $this;
   }

   public function addCcAddresses(array $addrs): self {
       foreach ($addrs as $address) {
           if (is_string($address)) {
               $list = explode('@', $address);
               $this->cc_addresses[] = [
                   'name' => $list[0],
                   'value' => $address,
               ];
           }
           elseif (is_array($address)) {
               if(isset($address['name']) && isset($address['value'])) {
                   $this->cc_addresses[] = $address;
               }
           }
       }
       return $this;
   }

   public function addBccAddresses(array $addrs): self {
       foreach ($addrs as $address) {
           if (is_string($address)) {
               $list = explode('@', $address);
               $this->bcc_addresses[] = [
                   'name' => $list[0],
                   'value' => $address,
               ];
           }
           elseif (is_array($address)) {
               if(isset($address['name']) && isset($address['value'])) {
                   $this->bcc_addresses[] = $address;
               }
           }
       }
       return $this;
   }

   public function addReplyTo(string $address): self
   {
       $this->reply_to = $address;
       return $this;
   }

   public function enveloper(ServerSettings $settings): ?PHPMailer
   {
       try {
           $mail = new PHPMailer(true);

           // Server settings
           $mail->isSMTP();
           $mail->Host       = $settings->getHost();
           $mail->SMTPAuth   = true;
           $mail->Username   = $settings->getUsername();
           $mail->Password   = $settings->getPassword();
           $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
           $mail->Port       = $settings->getPort();

           // Recipients
           $mail->setFrom($settings->getUsername(), $settings->getUsername());

           foreach($this->to_addresses as $address) {
               $mail->addAddress($address['value'], $address['name']);
           }

           foreach($this->cc_addresses as $address) {
               $mail->addCC($address['value'], $address['name']);
           }

           foreach($this->bcc_addresses as $address) {
               $mail->addBCC($address['value'], $address['name']);
           }

           if (!empty($this->reply_to)) {
               $mail->addReplyTo($this->reply_to);
           }

           // Content
           if (!empty($this->headers['Content-Type']) && $this->headers['Content-Type'] === 'text/html; charset=UTF-8') {
               $mail->isHTML(true);
           }

           $mail->Subject = $this->params['subject'];
           $mail->Body    = $this->params['body'];
           $mail->AltBody = $this->params['altBody'] ?? null;

           foreach($this->attachments as $attachment) {
               $mail->addAttachment($attachment['content'], $attachment['name']);
           }

           // Add custom headers
           foreach ($this->headers as $name => $value) {
               $mail->addCustomHeader($name, $value);
           }

           return $mail;
       } catch (\Throwable $e) {
           file_put_contents('here', $e->getMessage());
           return null;
       }
   }

   public static function create(string $subject, string $body): self
   {
       $envelope = new self();
       $envelope->addParam('subject', $subject);
       $envelope->addParam('body', $body);
       return $envelope;
   }

}