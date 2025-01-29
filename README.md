# Mailer Library

## Overview
The Mailer Library is a PHP-based solution for managing and sending emails using SMTP configurations. It includes classes for handling server settings, managing emails, and creating email envelopes. Below is a comprehensive guide to its usage.

---

## Installation

Ensure you have Composer installed. Add the library to your project by running:

```bash
composer require your-library-name
```

Include the autoload file in your `index.php` or main script:

```php
require_once "vendor/autoload.php";
```

---

## Configuration

### Server Settings
Define your SMTP server settings using an associative array:

```php
$server = [
    "host" => "smtp.gmail.com",
    "port" => 465,
    "username" => "test@gmail.com",
    "password" => "your-password"
];
```

Alternatively, you can register server settings globally using:

```php
\Simp\Environment\Environment::create('mail_server_one', $server);
```

---

## Usage

### Creating a Mail Manager
Instantiate a mail manager with your server settings:

```php
$mail_manager = \Simp\Mail\Mail\MailManager::mailManager(smtp_array: $server);
```

Or use globally registered server settings:

```php
$mail_manager2 = \Simp\Mail\Mail\MailManager::mailManager('mail_server_one');
```

### Creating an Envelope
An envelope represents an email to be sent. Create one as follows:

```php
$envelope = \Simp\Mail\Mail\Envelope::create(
    "Testing Email",
    "<h1>Hello, this is a test email</h1><p>This is the body content.</p>"
);
$envelope->addToAddresses(["recipient@example.com"]);
```

You can create multiple envelopes if needed:

```php
$envelope1 = \Simp\Mail\Mail\Envelope::create(
    "Another Test Email",
    "<h1>Another test</h1><p>Second email body.</p>"
);
$envelope1->addToAddresses(["another@example.com"]);
```

### Adding Envelopes to the Mail Manager
Add envelopes to the mail manager for processing:

```php
$mail_manager->addEnvelope($envelope);
$mail_manager2->addEnvelope($envelope1);
```

### Sending Emails
Process the envelopes to send emails:

```php
$result = $mail_manager->processEnvelopes();
$mail_manager2->processEnvelopes();
```

---

## Example
Here is the complete example of using the library:

```php
require_once "vendor/autoload.php";

$server = [
    "host" => "smtp.gmail.com",
    "port" => 465,
    "username" => "test@gmail.com",
    "password" => "your-password"
];

\Simp\Environment\Environment::create('mail_server_one', $server);

$mail_manager = \Simp\Mail\Mail\MailManager::mailManager(smtp_array: $server);
$mail_manager2 = \Simp\Mail\Mail\MailManager::mailManager('mail_server_one');

$envelope = \Simp\Mail\Mail\Envelope::create("Testing Email", "<h1>Hello this is a test email</h1><p>ok let see the paragraph</p>");
$envelope->addToAddresses(["exp1@gmail.com"]);

$envelope1 = \Simp\Mail\Mail\Envelope::create("Testing Email 2", "<h1>Hello this is a test email</h1><p>ok let see the paragraph</p>");
$envelope1->addToAddresses(["exp2@gmail.com"]);

$envelope2 = \Simp\Mail\Mail\Envelope::create("Testing Email 3", "<h1>Hello this is a test email</h1><p>ok let see the paragraph</p>");
$envelope2->addToAddresses(["exp3@gmail.com"]);

$mail_manager->addEnvelope($envelope);
$mail_manager2->addEnvelope($envelope1);
$mail_manager2->addEnvelope($envelope2);

$result = $mail_manager->processEnvelopes();
$mail_manager2->processEnvelopes();

$envelope = \Simp\Mail\Mail\Envelope::create('Lorem ipsum dolor sit amet ', 'Lorem ipsum dolor sit amet consectetur');
$envelope->addToAddresses(['exp1@gmail.com','exp2@gmail.com']);
$envelope->addCcAddresses(['exp3@gmail.com','exp4@gmail.com']);
$envelope->addBccAddresses(['exp5@gmail.com','exp6@gmail.com']);

// Add Attachment
$envelope->addAttachment("chance.pdf", "financial-report.pdf");

$mail_manager = \Simp\Mail\Mail\MailManager::mailManager('mail_server_one');
$mail_manager->addEnvelope($envelope)->processEnvelopes();
```

---

## License

Specify the license details here.

---

## Contributing

Feel free to contribute by submitting issues or pull requests on GitHub.

