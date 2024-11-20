<?php

namespace App\Controllers;

use App\Traits\Renderable; 
use App\Controllers\BaseController;

class HomeController extends BaseController
{
    use Renderable; 

    public function index()
    {
        $template = 'login'; 
        $data = [
            'title' => 'Login',
        ];
        $this->render($template, $data); 
    }
}
