<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        $session = session();
        
        // If user is already logged in, redirect to appropriate page
        if ($session->get('isLoggedIn')) {
            $roleId = $session->get('role_id');
            if ($roleId == 1) {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/coffee');
        }
        
        return view('login');
    }

    public function authenticate()
    {
        $session = session();
        $db = \Config\Database::connect();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validate input
        if (empty($username) || empty($password)) {
            $session->setFlashdata('msg', 'Please enter both username and password.');
            return redirect()->to('/');
        }

        $user = $db->table('users')->where('username', $username)->get()->getRow();

        if ($user) {
            if (password_verify($password, $user->password)) {
                // Regenerate session ID for security (prevents session fixation attacks)
                $session->regenerate(true);
                
                // Get user's role
                $userRole = $db->table('user_roles')
                    ->where('user_id', $user->id)
                    ->get()
                    ->getRow();
                
                $roleId = $userRole ? $userRole->role_id : 2; // Default to customer if no role
                
                // Set session data with last activity timestamp
                $session->set([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $roleId,
                    'isLoggedIn' => true,
                    'last_activity' => time(),
                    'login_time' => time()
                ]);
                
                // Log successful login (optional - can be removed if not needed)
                log_message('info', "User {$user->username} (ID: {$user->id}) logged in successfully.");
                
                // Redirect admin to admin dashboard, customer to coffee page
                if ($roleId == 1) {
                    return redirect()->to('/admin/dashboard');
                }
                
                return redirect()->to('/coffee');
            }
        }

        // Log failed login attempt (optional - can be removed if not needed)
        log_message('warning', "Failed login attempt for username: {$username}");
        
        $session->setFlashdata('msg', 'Wrong password or username.');
        return redirect()->to('/');
    }
    
    public function logout()
    {
        $session = session();
        
        // Log logout (optional)
        if ($session->get('username')) {
            log_message('info', "User {$session->get('username')} (ID: {$session->get('user_id')}) logged out.");
        }
        
        // Clear all session data
        $session->remove(['user_id', 'username', 'email', 'role_id', 'isLoggedIn', 'last_activity', 'login_time']);
        
        // Regenerate session ID to prevent session fixation
        $session->regenerate(true);
        
        // Destroy session completely
        $session->destroy();
        
        $session->setFlashdata('msg', 'You have been logged out successfully.');
        return redirect()->to('/');
    }
}
