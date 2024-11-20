<?php

namespace App\Controllers;

class WelcomeController extends BaseController
{
    public function showWelcome()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $data = [
            'title' => 'Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name']
        ];

        return $this->render('dashboard', $data);
    }
}
