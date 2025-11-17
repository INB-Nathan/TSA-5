<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Admin Authentication Filter
 * Protects admin routes requiring admin privileges
 * 
 * Extends AuthFilter functionality by also checking if user has admin role (role_id = 1).
 * Validates authentication, session timeout (2 hours), and admin role.
 * Redirects non-admin users to coffee page.
 */
class AdminFilter implements FilterInterface
{
    /**
     * Execute before request processing
     * Validates user authentication, session timeout, and admin role
     * 
     * Session timeout: 2 hours (7200 seconds)
     * Admin role check: role_id must equal 1
     * Updates last_activity timestamp on successful validation
     * 
     * @param RequestInterface $request Current request
     * @param mixed $arguments Filter arguments
     * @return \CodeIgniter\HTTP\RedirectResponse|null Redirects to login if not authenticated, or coffee page if not admin
     */
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

    /**
     * Execute after request processing
     * Currently unused - reserved for future functionality
     * 
     * @param RequestInterface $request Current request
     * @param ResponseInterface $response Current response
     * @param mixed $arguments Filter arguments
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}
