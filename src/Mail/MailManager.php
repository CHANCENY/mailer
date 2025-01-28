<?php

namespace Simp\Mail\Mail;

use Composer\Autoload\ClassLoader;
use Simp\Mail\ServerSettings\ServerSettings;

class MailManager
{
    private ServerSettings $serverSettings;

    private array $envelopes;

    private array $background_process;

    public function __construct(?string $environment = null, array $smtp_settings = [])
    {
        if ($environment) {
            $this->serverSettings = ServerSettings::createFromEnvironment($environment);
        }
        else {
            $this->serverSettings = ServerSettings::createFromArray($smtp_settings);
        }
    }

    public function addEnvelope(Envelope $envelope): MailManager
    {
        $this->envelopes[] = $envelope;
        return $this;
    }

    public function processEnvelopes(): bool|array|null
    {
        if (count($this->envelopes) === 0) {
            return null;
        }
        $status = [];
        /**@var Envelope $envelope**/
        foreach ($this->envelopes as $envelope) {
          $mailer = $envelope->enveloper($this->serverSettings);
          $status[] = $mailer->send();
        }
        if (count($status) === 1) {
            return reset($status);
        }
        return $status;
    }

    public static function mailManager(?string $environment_key = null, array $smtp_array = []): MailManager
    {
        return new MailManager($environment_key, $smtp_array);
    }

    public function getServerSettings(): ServerSettings
    {
        return $this->serverSettings;
    }
}