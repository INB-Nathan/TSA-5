<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        // TODO: Add authentication logic here
        // 1. Validate input
        // 2. Check credentials against the database
        // 3. Set session data
        // 4. Redirect to the appropriate page
    }
}
