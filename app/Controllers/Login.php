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
                $session->set([
                    'username' => $user->username,
                    'isLoggedIn' => TRUE
                ]);
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
