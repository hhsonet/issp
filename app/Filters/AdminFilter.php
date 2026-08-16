<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('is_logged_in') === true && session()->get('is_active') === true && session()->get('role') === 'admin') {
            return null;
        }

        if (session()->get('is_logged_in') !== true) {
            session()->setFlashdata('warning', 'Please sign in to continue.');
            session()->set('return_to', ltrim((string) $request->getUri()->getPath(), '/'));
            return redirect()->to(site_url('login'));
        }

        return redirect()->to(site_url('dashboard'))->with('error', 'You do not have permission to access that page.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
