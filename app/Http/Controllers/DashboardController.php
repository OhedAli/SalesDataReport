<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        
        return view('admin-dashboard');
    }
    public function getRecord(){
       // $blogs = DB::connection('mysql2')->table("admin")->get();

        
    }
}
