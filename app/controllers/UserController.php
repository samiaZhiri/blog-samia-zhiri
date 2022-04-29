<?php

namespace App\Controllers;

use App\https\HttpRequest;
use Controller;

class UserController extends Controller
{
    public function store()
    {
        return $this->view('users.store');
    }
    public function login(HttpRequest $request)
    {
        $request->validator([
            'username' => ['required', 'max:6'],
            'password' => ['required']
        ]);
    }
}
