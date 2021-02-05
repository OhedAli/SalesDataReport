<?php

namespace App\Http\Controllers;
namespace App\Http\Model\Saleslogs;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $total_customers = Saleslogs::where('type', 'id')->count();


        print($total_customers);
        return view('admin-dashboard');
    }
    public function getRecord(){
       // $blogs = DB::connection('mysql2')->table("admin")->get();

        
    }
}
