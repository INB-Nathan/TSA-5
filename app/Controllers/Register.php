<?php

namespace App\Controllers;

class Register extends BaseController
{
    public function index()
    {
        return view('register');
    }

    public function create()
    {
        // TODO: Add registration logic here
        // 1. Validate input
        // 2. Check if user already exists
        // 3. Create new user in the database
        // 4. Redirect to the login page with a success message
    }
}
