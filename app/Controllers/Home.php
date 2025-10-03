<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Welcome - BJS Accounting System',
        ];

        return view('welcome_message', $data);
    }
}