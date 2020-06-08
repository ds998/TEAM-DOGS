<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserControllerFilter implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        $session=session();
        if($session->has('controller') && $session->get('controller')!='UserController'){
            return redirect()->to(site_url($session->get('controller')));
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}