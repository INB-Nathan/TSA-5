<?php

namespace App\Controllers;

/**
 * Account Controller
 * Handles user account management: viewing account info, changing password, and deleting account
 */
class Account extends BaseController
{
    /**
     * Display account settings page
     * Shows user information and provides forms for password change and account deletion
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/');
        }
        
        $user = $db->table('users')
            ->where('id', $userId)
            ->get()
            ->getRow();
        
        $data = [
            'user' => $user
        ];
        
        return view('account/index', $data);
    }
    
    /**
     * Change user password
     * Validates current password, validates new password requirements, updates password, and sends notification email
     * 
     * Password requirements: min 8 chars, must contain uppercase, lowercase, number, and special character
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function changePassword()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/');
        }
        
        $rules = [
            'current_password' => 'required',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            $session->setFlashdata('validation_errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        $user = $db->table('users')
            ->where('id', $userId)
            ->get()
            ->getRow();
        
        $currentPassword = $this->request->getPost('current_password');
        if (!password_verify($currentPassword, $user->password)) {
            $session->setFlashdata('error', 'Current password is incorrect.');
            return redirect()->to('/account');
        }
        
        $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $db->table('users')
            ->where('id', $userId)
            ->update(['password' => $newPassword]);
        
        // Send email notification about password change
        try {
            $emailService = new \App\Libraries\EmailService();
            $emailService->sendNotificationEmail(
                $user->email,
                'Password Changed Successfully',
                '<p>Hello ' . esc($user->username) . ',</p><p>Your password has been successfully changed. If you did not make this change, please contact support immediately.</p>',
                ['username' => $user->username]
            );
        } catch (\Exception $e) {
            log_message('error', 'Failed to send password change email: ' . $e->getMessage());
        }
        
        $session->setFlashdata('msg', 'Password changed successfully.');
        return redirect()->to('/account');
    }
    
    /**
     * Request password reset
     * Processes password reset request from login page form
     * 
     * Works without authentication - accepts email address from form.
     * For security, always shows success message even if email doesn't exist (prevents email enumeration).
     * Token expires in 1 hour. Only one active reset token per user (replaces previous tokens)
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function requestPasswordReset()
    {
        // Process form submission on POST request
        $db = \Config\Database::connect();
        $session = session();
        
        // Get email from POST (can be called from login page without auth)
        $email = $this->request->getPost('email');
        
        if (empty($email)) {
            $session->setFlashdata('error', 'Please enter your email address.');
            return redirect()->to('/');
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->setFlashdata('error', 'Please enter a valid email address.');
            return redirect()->to('/');
        }
        
        // Find user by email
        $user = $db->table('users')
            ->where('email', $email)
            ->get()
            ->getRow();
        
        // For security, always show success message (prevents email enumeration attacks)
        // But only actually send email if user exists
        if ($user) {
            // Generate secure random token
            $resetToken = bin2hex(random_bytes(32));
            $tokenExpires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store token in database
            try {
                $db->table('users')
                    ->where('id', $user->id)
                    ->update([
                        'reset_token' => $resetToken,
                        'reset_token_expires' => $tokenExpires
                    ]);
            } catch (\Exception $e) {
                // If columns don't exist, log error with details
                $errorMessage = $e->getMessage();
                log_message('error', 'Password reset token storage failed: ' . $errorMessage);
                
                // Check if error is about missing columns
                if (strpos($errorMessage, 'Unknown column') !== false || strpos($errorMessage, "doesn't exist") !== false) {
                    $session->setFlashdata('error', 'Password reset feature requires database migration. Please run: migrations/add_password_reset_columns.sql');
                } else {
                    $session->setFlashdata('error', 'Password reset request failed. Please contact support.');
                }
                return redirect()->to('/');
            }
            
            // Send password reset email
            try {
                $emailService = new \App\Libraries\EmailService();
                $emailService->sendPasswordResetEmail($user->email, $resetToken, $user->username);
            } catch (\Exception $e) {
                log_message('error', 'Failed to send password reset email: ' . $e->getMessage());
            }
        }
        
        // Always show success message (security best practice - prevents email enumeration)
        $session->setFlashdata('msg', 'If an account exists with that email address, a password reset link has been sent. Please check your inbox.');
        
        // Redirect to login page (or account page if user is logged in)
        if ($session->get('isLoggedIn')) {
            return redirect()->to('/account');
        }
        
        return redirect()->to('/');
    }
    
    /**
     * Reset password using token
     * 
     * Handles both GET (display form) and POST (process reset) requests:
     * 
     * GET: Validates token and expiration from URL, displays reset password form if valid.
     *      Redirects to login if token invalid/expired.
     * 
     * POST: Validates token, new password requirements (min 8 chars, uppercase, lowercase, 
     *       number, special char), updates password, clears reset token, sends confirmation email.
     *       Redirects to login on success or back to form with errors on validation failure.
     * 
     * Token expiration: 1 hour from generation
     * 
     * @param string|null $token Reset token from email link (GET) or null (POST reads from form)
     * @return \CodeIgniter\HTTP\RedirectResponse|string View on GET success, redirect otherwise
     */
    public function resetPassword($token = null)
    {
        $db = \Config\Database::connect();
        $session = session();
        $method = strtoupper($this->request->getMethod());
        
        // If token provided via GET, show reset form
        if ($token && $method === 'GET') {
            // Validate token
            $user = $db->table('users')
                ->where('reset_token', $token)
                ->where('reset_token_expires >', date('Y-m-d H:i:s'))
                ->get()
                ->getRow();
            
            if (!$user) {
                $session->setFlashdata('error', 'Invalid or expired password reset token.');
                return redirect()->to('/');
            }
            
            return view('account/reset_password', ['token' => $token]);
        }
        
        // Handle POST request to actually reset password
        if ($method === 'POST') {
            $token = $this->request->getPost('token');
            $password = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');
            
            // Validate token
            $user = $db->table('users')
                ->where('reset_token', $token)
                ->where('reset_token_expires >', date('Y-m-d H:i:s'))
                ->get()
                ->getRow();
            
            if (!$user) {
                $session->setFlashdata('error', 'Invalid or expired password reset token.');
                return redirect()->to('/');
            }
            
            // Validate password
            $rules = [
                'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
                'confirm_password' => 'required|matches[password]'
            ];
            
            if (!$this->validate($rules)) {
                $session->setFlashdata('validation_errors', $this->validator->getErrors());
                return redirect()->to('/reset-password/' . $token)->withInput();
            }
            
            // Update password and clear reset token
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $db->table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => $hashedPassword,
                    'reset_token' => null,
                    'reset_token_expires' => null
                ]);
            
            // Send notification email
            try {
                $emailService = new \App\Libraries\EmailService();
                $emailService->sendNotificationEmail(
                    $user->email,
                    'Password Reset Successful',
                    '<p>Hello ' . esc($user->username) . ',</p><p>Your password has been successfully reset. If you did not make this change, please contact support immediately.</p>',
                    ['username' => $user->username]
                );
            } catch (\Exception $e) {
                log_message('error', 'Failed to send password reset confirmation email: ' . $e->getMessage());
            }
            
            $session->setFlashdata('msg', 'Your password has been reset successfully. Please login with your new password.');
            return redirect()->to('/');
        }
        
        return redirect()->to('/');
    }
    
    /**
     * Delete user account permanently
     * Requires password confirmation. Destroys session and deletes user record from database.
     * 
     * Warning: This action is irreversible and will delete all user data
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteAccount()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/');
        }
        
        $sudoPassword = $this->request->getPost('sudo_password');
        
        $user = $db->table('users')
            ->where('id', $userId)
            ->get()
            ->getRow();
        
        if (!password_verify($sudoPassword, $user->password)) {
            $session->setFlashdata('error', 'Password incorrect. Account deletion cancelled.');
            return redirect()->to('/account');
        }
        
        $db->table('users')->where('id', $userId)->delete();
        
        $session->destroy();
        
        $session->setFlashdata('msg', 'Your account has been deleted successfully.');
        return redirect()->to('/');
    }
}
