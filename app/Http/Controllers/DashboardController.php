<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;

class DashboardController extends Controller
{
    public function index(){
       
        $lastweek = date('Y-m-d'.strtotime('-7 days'));
        $lastmonth = date('Y-m-d'.strtotime('-7 days'));
        $todayDate = date('Y-m-d'); 
        $todayorders = Saleslogs::where('create_at',$todayDate)->get();
        $todaycount = Saleslogs::where('create_at',$todayDate)->count();
        
        $weeklycount = Saleslogs::whereBetween('create_at',[$lastweek,$todayDate])->count();
        $monthlycount = Saleslogs::whereBetween('create_at',[$lastmonth,$todayDate])->count();
      
        
        return view('admin-dashboard',compact('todaycount','weeklycount','monthlycount'));
    }
    public function getRecord(){
       // $blogs = DB::connection('mysql2')->table("admin")->get();

        
    }
}
