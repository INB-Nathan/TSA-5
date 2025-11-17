<?php

namespace App\Controllers;

/**
 * Register Controller
 * Handles user registration: form display and user account creation
 */
class Register extends BaseController
{
    /**
     * Display registration page
     * Redirects to dashboard/coffee page if user is already logged in
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        $session = session();
        
        if ($session->get('isLoggedIn')) {
            $roleId = $session->get('role_id');
            if ($roleId == 1) {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/coffee');
        }
        
        return view('register');
    }

    /**
     * Create new user account
     * 
     * Validates registration data:
     * - Username: 3-255 chars, alphanumeric + underscore, unique
     * - Email: valid format, unique
     * - Password: min 8 chars, must contain uppercase, lowercase, number, and special character
     * 
     * Creates user account, assigns default role (role_id=2), sends welcome email (non-blocking),
     * and redirects to login page
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function create()
    {
        $validation =  \Config\Services::validation();

        $rules = [
            'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]|regex_match[/^[a-zA-Z0-9_]+$/]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return view('register', [
                'validation' => $this->validator
            ]);
        }

        $db = \Config\Database::connect();

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        $db->table('users')->insert($data);
        $userId = $db->insertID();

        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => 2]);

        // Send welcome email
        try {
            $emailService = new \App\Libraries\EmailService();
            $emailService->sendWelcomeEmail(
                $this->request->getPost('email'),
                $this->request->getPost('username')
            );
        } catch (\Exception $e) {
            // Log error but don't fail registration
            log_message('error', 'Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->to('/')->with('msg', 'Registration successful. Please login.');
    }
}
