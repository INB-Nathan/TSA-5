<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('msg', 'Please login to access this page.');
            return redirect()->to('/');
        }
        
        $lastActivity = $session->get('last_activity');
        $sessionTimeout = 7200;
        
        if ($lastActivity && (time() - $lastActivity) > $sessionTimeout) {
            $session->destroy();
            $session->setFlashdata('msg', 'Your session has expired. Please login again.');
            return redirect()->to('/');
        }
        
        if ($session->get('role_id') != 1) {
            $session->setFlashdata('msg', 'Access denied. Admin privileges required.');
            return redirect()->to('/coffee');
        }
        
        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}
