<?php

namespace App\Controllers;

class Coffee extends BaseController
{
    public function index()
    {
        return view('coffee_view');
    }
}
