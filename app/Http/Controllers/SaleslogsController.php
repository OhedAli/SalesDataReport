<?php

namespace App\Http\Controllers;
use App\Models\Saleslogs;
use Illuminate\Http\Request;

class SaleslogsController extends Controller
{
    public function index(){
        $dateFrom = '2021-01-14';
        $dateTo = '2021-02-04';
        $todayDate = date("Y-m-d"); 
        $todayorders = Saleslogs::where('create_at',$todayDate)->get();
        $todaycount = Saleslogs::where('create_at',$todayDate)->count();
        $orderscount = Saleslogs::whereBetween('create_at',[$dateFrom,$dateTo])->count();
        $orderscount = Saleslogs::whereBetween('create_at',[$dateFrom,$dateTo])->count();
        //print_r($todayorders);
       // echo $orderscount;
       return view('saleslogs', compact('todayorders'));
    }
}
