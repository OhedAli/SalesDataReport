<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;

class DashboardController extends Controller
{
    public function index(){
       
        $lastweek = date('Y-m-d 00:00:00', strtotime('-6 days'));
        $lastmonth = date('Y-m-d 00:00:00', strtotime('-29 days'));
        $todayDate = date('Y-m-d');

        $todayDate_start = date('Y-m-d 00:00:00'); 
        $todayDate_end = date('Y-m-d 23:59:00');

        $todaycount = Saleslogs::whereBetween('create_at',[$todayDate_start, $todayDate_end])->count();
        
        $weeklycount = Saleslogs::whereBetween('create_at',[$lastweek,$todayDate_end])->count();
        $monthlycount = Saleslogs::whereBetween('create_at',[$lastmonth,$todayDate_end])->count();
      
        
        return view('admin-dashboard',compact('todaycount','weeklycount','monthlycount'));
    }
    public function getRecord(){
       // $blogs = DB::connection('mysql2')->table("admin")->get();

        
    }
}