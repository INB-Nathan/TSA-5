<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

/**
 * Email Service
 * Handles all email operations: welcome emails, password resets, notifications, and custom emails
 */
class EmailService
{
    protected $email;
    protected $config;

    /**
     * Initialize email service with CodeIgniter email configuration
     */
    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');
    }

    /**
     * Send welcome email to new user
     * Uses 'emails/welcome' view template
     * 
     * @param string $to Recipient email address
     * @param string $username Username for personalization
     * @return bool Success status
     */
    public function sendWelcomeEmail($to, $username)
    {
        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setTo($to);
        $this->email->setSubject('Welcome to ' . $this->config->fromName);
        $this->email->setMailType('html');
        
        $data = [
            'username' => $username,
            'siteName' => $this->config->fromName
        ];
        
        $message = view('emails/welcome', $data);
        $this->email->setMessage($message);
        
        return $this->email->send();
    }

    /**
     * Send password reset email with reset link
     * Uses 'emails/password_reset' view template
     * 
     * @param string $to Recipient email address
     * @param string $resetToken Password reset token
     * @param string $username Username for personalization
     * @return bool Success status
     */
    public function sendPasswordResetEmail($to, $resetToken, $username)
    {
        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setTo($to);
        $this->email->setSubject('Password Reset Request');
        $this->email->setMailType('html');
        
        $resetLink = base_url('reset-password/' . $resetToken);
        
        $data = [
            'username' => $username,
            'resetLink' => $resetLink,
            'siteName' => $this->config->fromName
        ];
        
        $message = view('emails/password_reset', $data);
        $this->email->setMessage($message);
        
        return $this->email->send();
    }

    /**
     * Send generic notification email
     * Uses 'emails/notification' view template with custom message and optional data
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message HTML message content
     * @param array $data Additional data to pass to view template
     * @return bool Success status
     */
    public function sendNotificationEmail($to, $subject, $message, $data = [])
    {
        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMailType('html');
        
        $emailData = array_merge([
            'message' => $message,
            'siteName' => $this->config->fromName
        ], $data);
        
        $emailMessage = view('emails/notification', $emailData);
        $this->email->setMessage($emailMessage);
        
        return $this->email->send();
    }

    /**
     * Send custom email (plain text or HTML)
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message Email body content
     * @param bool $isHtml Whether message is HTML (default: true)
     * @return bool Success status
     */
    public function sendEmail($to, $subject, $message, $isHtml = true)
    {
        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMailType($isHtml ? 'html' : 'text');
        $this->email->setMessage($message);
        
        return $this->email->send();
    }

    /**
     * Get last email error message
     * Returns debug information from CodeIgniter email service
     * 
     * @return string Error/debug message
     */
    public function getError()
    {
        return $this->email->printDebugger(['headers']);
    }
}
