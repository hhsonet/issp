<?php

namespace App\Filters;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('is_logged_in') === true) {
            return null;
        }

        $message = 'Please sign in to continue.';

        session()->setFlashdata('warning', $message);
        session()->set('return_to', ltrim((string) $request->getUri()->getPath(), '/'));
        return redirect()->to(site_url('login'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
