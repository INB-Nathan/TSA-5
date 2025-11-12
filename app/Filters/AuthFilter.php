<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('msg', 'Please login to access this page.');
            return redirect()->to('/');
        }
        
        // Check session timeout (2 hours = 7200 seconds)
        $lastActivity = $session->get('last_activity');
        $sessionTimeout = 7200; // 2 hours
        
        if ($lastActivity && (time() - $lastActivity) > $sessionTimeout) {
            // Session expired
            $session->destroy();
            $session->setFlashdata('msg', 'Your session has expired. Please login again.');
            return redirect()->to('/');
        }
        
        // Update last activity timestamp
        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}
