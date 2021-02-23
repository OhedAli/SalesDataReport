<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallsController extends Controller
{
    public function index()
    {
        return view('calls-details');
    }
}
