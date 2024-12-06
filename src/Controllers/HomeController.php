<?php

namespace App\Controllers;

use App\Traits\Renderable; 
use App\Controllers\BaseController;

class HomeController extends BaseController
{
    use Renderable; 

    public function index()
    {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
            header('Location: /dashboard');
            exit;
        }

        $template = 'login'; 
        $data = [
            'title' => 'Login',
        ];
        $this->render($template, $data); 
    }
}
