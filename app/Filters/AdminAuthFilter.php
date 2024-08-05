<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        
        $uri = $request->getUri();

        
        if (!$session->get('isLoggedIn') || !$session->get('isAdmin')) {
            return redirect()->to('/signin_login')->with('message', 'Please log in as an admin.');
        }

        
        $path = $uri->getPath();
        if ($session->get('isLoggedIn') && $session->get('isAdmin') && 
            $path ==='signin_login') {
            return redirect()->to('/admin_dashboard')->with('message', 'You are already logged in.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
       
    }
}
