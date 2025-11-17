<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Email address of the sender
     * Can be overridden via environment variable: email.fromEmail
     */
    public string $fromEmail  = 'noreply@brewkaholic.com';

    /**
     * Name of the sender
     * Can be overridden via environment variable: email.fromName
     */
    public string $fromName   = 'BrewKaholic';

    public function __construct()
    {
        parent::__construct();

        // Override with environment variables if set
        $this->fromEmail = env('email.fromEmail', $this->fromEmail);
        $this->fromName = env('email.fromName', $this->fromName);
        $this->protocol = env('email.protocol', $this->protocol);
        $this->SMTPHost = env('email.SMTPHost', $this->SMTPHost);
        $this->SMTPUser = env('email.SMTPUser', $this->SMTPUser);
        $this->SMTPPass = env('email.SMTPPass', $this->SMTPPass);
        $this->SMTPPort = (int) env('email.SMTPPort', $this->SMTPPort);
        $this->SMTPCrypto = env('email.SMTPCrypto', $this->SMTPCrypto);
        $this->SMTPTimeout = (int) env('email.SMTPTimeout', $this->SMTPTimeout);
        $this->mailType = env('email.mailType', $this->mailType);
    }

    /**
     * Comma-separated list of recipient email addresses
     */
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     * 
     * For production, use 'smtp' and configure SMTP settings below
     * For local development, 'mail' or 'sendmail' may work
     */
    public string $protocol = 'mail';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     * Example: smtp.gmail.com, smtp.mailtrap.io, smtp.sendgrid.net
     * Can be overridden via environment variable: email.SMTPHost
     */
    public string $SMTPHost = '';

    /**
     * SMTP Username
     * Can be overridden via environment variable: email.SMTPUser
     */
    public string $SMTPUser = '';

    /**
     * SMTP Password
     * Can be overridden via environment variable: email.SMTPPass
     */
    public string $SMTPPass = '';

    /**
     * SMTP Port
     * Common ports: 25 (non-encrypted), 587 (TLS), 465 (SSL)
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     * Set to 'html' to send HTML emails (recommended)
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
