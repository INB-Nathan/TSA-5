<?php

namespace App\Controllers;

class Register extends BaseController
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
        
        return view('register');
    }

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


        return redirect()->to('/')->with('msg', 'Registration successful. Please login.');
    }
}
