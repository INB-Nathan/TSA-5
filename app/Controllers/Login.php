<?php

namespace App\Controllers;

/**
 * Login Controller
 * Handles user authentication: login form display, authentication, and logout
 */
class Login extends BaseController
{
    /**
     * Display login page
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
        
        return view('login');
    }

    /**
     * Authenticate user login
     * Validates credentials, retrieves user role, creates session, and redirects based on role
     * 
     * Session includes: user_id, username, email, role_id, isLoggedIn, last_activity, login_time
     * Role-based redirect: Admin (role_id=1) -> /admin/dashboard, User -> /coffee
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function authenticate()
    {
        $session = session();
        $db = \Config\Database::connect();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            $session->setFlashdata('msg', 'Please enter both username and password.');
            return redirect()->to('/');
        }

        $user = $db->table('users')->where('username', $username)->get()->getRow();

        if ($user) {
            if (password_verify($password, $user->password)) {
                $session->regenerate(true);
                
                $userRole = $db->table('user_roles')
                    ->where('user_id', $user->id)
                    ->get()
                    ->getRow();
                
                $roleId = $userRole ? $userRole->role_id : 2;
                
                $session->set([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $roleId,
                    'isLoggedIn' => true,
                    'last_activity' => time(),
                    'login_time' => time()
                ]);
                
                log_message('info', "User {$user->username} (ID: {$user->id}) logged in successfully.");
                
                if ($roleId == 1) {
                    return redirect()->to('/admin/dashboard');
                }
                
                return redirect()->to('/coffee');
            }
        }

        log_message('warning', "Failed login attempt for username: {$username}");
        
        $session->setFlashdata('msg', 'Wrong password or username.');
        return redirect()->to('/');
    }
    
    /**
     * Logout user
     * Removes session data, regenerates session ID, and destroys session
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        $session = session();
        
        if ($session->get('username')) {
            log_message('info', "User {$session->get('username')} (ID: {$session->get('user_id')}) logged out.");
        }
        
        $session->remove(['user_id', 'username', 'email', 'role_id', 'isLoggedIn', 'last_activity', 'login_time']);
        
        $session->regenerate(true);

        $session->destroy();
        
        $session->setFlashdata('msg', 'You have been logged out successfully.');
        return redirect()->to('/');
    }
}
