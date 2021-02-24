<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;


class DashboardController extends Controller
{
    
    public function index(){

        date_default_timezone_set("America/Chicago");

        if(date('D') != 'Mon'){
            $lastweek = date('Y-m-d 00:00:00', strtotime('last Monday'));
            $Secondlastweek = date('Y-m-d 00:00:00', strtotime('-7 days', strtotime($lastweek)));
        }
        else{
            $lastweek = date('Y-m-d 00:00:00');
            $Secondlastweek = date('Y-m-d 00:00:00', strtotime('last Monday'));
        }
           
        $lastmonth = date('Y-m-1 00:00:00');
        $Secondlastmonth = date('Y-m-1 00:00:00', strtotime('-29 days'));
        $todayDate_start = date('Y-m-d 00:00:00'); 
        $yesterdayDate_start = date('Y-m-d 00:00:00', strtotime('-1 days')); 
        $todayDate_end = date('Y-m-d 23:59:00');

        $todaycount = Saleslogs::whereBetween('create_at',[$todayDate_start, $todayDate_end])->count();
        $today_details = Saleslogs::whereBetween('create_at',[$todayDate_start, $todayDate_end])->get();
        $yesterdaycount = Saleslogs::whereBetween('create_at',[$yesterdayDate_start, $todayDate_start])->count();
        $dailydata = $this->FlagSighCheck($todaycount, $yesterdaycount);
        
        $weeklycount = Saleslogs::whereBetween('create_at',[$lastweek,$todayDate_end])->count();
        $weekly_details = Saleslogs::whereBetween('create_at',[$lastweek,$todayDate_end])->get();
        $Secondweeklycount = Saleslogs::whereBetween('create_at',[$Secondlastweek,$lastweek])->count();
        $weeklydata = $this->FlagSighCheck($weeklycount, $Secondweeklycount);
        
        $monthlycount = Saleslogs::whereBetween('create_at',[$lastmonth,$todayDate_end])->count();
        $monthly_details = Saleslogs::select('salesman', 'team', Saleslogs::raw('count(salesman) as sales_count '))->whereBetween('create_at',[$lastmonth,$todayDate_end])->groupBy('salesman','team')->get();
        $Secondmonthlycount = Saleslogs::whereBetween('create_at',[$Secondlastmonth,$lastmonth])->count();
        $monthlydata = $this->FlagSighCheck($monthlycount, $Secondmonthlycount);

        return view('admin-dashboard',compact('todaycount','weeklycount','monthlycount','dailydata','weeklydata','monthlydata','today_details','weekly_details','monthly_details'));
    }
    
    public function FlagSighCheck($maincount, $secondcount){
        $differentcount = $maincount-$secondcount;
        $weeklyflag = ($differentcount > 0 ? 'pos': 'neg') ;
        $differentcount = abs($differentcount);
        $differentcountP = $this->get_percentage($differentcount,$secondcount);
        $val = array($weeklyflag,$differentcountP,$secondcount);
        return $val;
        
    }

    public function get_percentage($differentcount, $secondcount)
        {
            if ( $secondcount > 0 ) 
            {
                return round((100/$secondcount)*$differentcount,2);
            } 
            else 
            {
                return 0;
            }
        }

   
      
}
