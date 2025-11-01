<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        $session = session();
        $db = \Config\Database::connect();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $db->table('users')->where('username', $username)->get()->getRow();

        if ($user) {
            if (password_verify($password, $user->password)) {
                // Get user's role
                $userRole = $db->table('user_roles')
                    ->where('user_id', $user->id)
                    ->get()
                    ->getRow();
                
                $roleId = $userRole ? $userRole->role_id : 2; // Default to customer if no role
                
                $session->set([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role_id' => $roleId,
                    'isLoggedIn' => TRUE
                ]);
                
                // Redirect admin to admin dashboard, customer to coffee page
                if ($roleId == 1) {
                    return redirect()->to('/admin/dashboard');
                }
                
                return redirect()->to('/coffee');
            }
        }

        $session->setFlashdata('msg', 'Wrong password or username.');
        return redirect()->to('/');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
