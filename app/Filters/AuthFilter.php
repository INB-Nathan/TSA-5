<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Authentication Filter
 * Protects routes requiring user authentication
 * 
 * Checks if user is logged in and validates session timeout (2 hours).
 * Redirects to login page if not authenticated or session expired.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Execute before request processing
     * Validates user authentication and session timeout
     * 
     * Session timeout: 2 hours (7200 seconds)
     * Updates last_activity timestamp on successful validation
     * 
     * @param RequestInterface $request Current request
     * @param mixed $arguments Filter arguments
     * @return \CodeIgniter\HTTP\RedirectResponse|null Redirects to login if not authenticated
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('msg', 'Please login to access this page.');
            return redirect()->to('/');
        }
        
        $lastActivity = $session->get('last_activity');
        $sessionTimeout = 7200; // 2 hours
        
        if ($lastActivity && (time() - $lastActivity) > $sessionTimeout) {
            $session->destroy();
            $session->setFlashdata('msg', 'Your session has expired. Please login again.');
            return redirect()->to('/');
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
