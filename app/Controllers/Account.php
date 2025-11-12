<?php

namespace App\Controllers;

class Account extends BaseController
{
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
    
    public function changePassword()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/');
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'current_password' => 'required',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
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
        
        $session->setFlashdata('msg', 'Password changed successfully.');
        return redirect()->to('/account');
    }
    
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
