<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;


class DashboardController extends Controller
{
<<<<<<< HEAD
    
    public function index(){
=======
    public function index(Request $request){

        $result = array();
>>>>>>> 1cf5d8e0b53a170ec973ff8bff42c4e1dc6d62a6

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
            $todayDate_end = date('Y-m-d 23:59:59');

            $result['todaycount'] = Saleslogs::whereBetween('create_at',[$todayDate_start, $todayDate_end])->count();
            $result['today_details'] = Saleslogs::select('salesman', 'team', Saleslogs::raw('count(salesman) as sales_count '))->whereBetween('create_at',[$todayDate_start, $todayDate_end])->groupBy('salesman','team')->get();
            $result['yesterdaycount'] = Saleslogs::whereBetween('create_at',[$yesterdayDate_start, $todayDate_start])->count();
            $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);
        
            $result['weeklycount'] = Saleslogs::whereBetween('create_at',[$lastweek,$todayDate_end])->count();
            $result['weekly_details'] = Saleslogs::select('salesman', 'team', Saleslogs::raw('count(salesman) as sales_count '))->whereBetween('create_at',[$lastweek,$todayDate_end])->groupBy('salesman','team')->get();
            $result['Secondweeklycount'] = Saleslogs::whereBetween('create_at',[$Secondlastweek,$lastweek])->count();
            $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['Secondweeklycount']);
        
            $result['monthlycount'] = Saleslogs::whereBetween('create_at',[$lastmonth,$todayDate_end])->count();
            $result['monthly_details'] =  Saleslogs::select('salesman','team',Saleslogs::raw('count(salesman) as sales_count '))
                               ->whereBetween('create_at',[$lastmonth,$todayDate_end])
                               ->groupBy('salesman','team')
                               ->get();
            $result['Secondmonthlycount'] = Saleslogs::whereBetween('create_at',[$Secondlastmonth,$lastmonth])->count();
            $result['monthlydata'] = $this->FlagSighCheck($result['monthlycount'], $result['Secondmonthlycount']);
        
        if($request->isMethod('get'))
        {
            $result['adv_range_flag'] = false;
            $result['adv_range_sales_details'] = '';
            $result['start_date'] = '';
            $result['end_date'] = '';
            $result['adv_range_sales_details'] = '';
            return view('admin-dashboard',compact('result'));
        }

        elseif($request->isMethod('post'))
        {
            $result['adv_range_flag'] = true;
            $start_range = $request->post('start_date').' 00:00:00';
            $end_range = $request->post('end_date').' 23:59:59';
            $result['start_date'] = date("dS F, Y", strtotime($request->post('start_date')));
            $result['end_date'] = date("dS F, Y", strtotime($request->post('end_date')));
            $result['adv_range_sales_count'] = Saleslogs::whereBetween('create_at',[$start_range, $end_range])->count();
            $result['adv_range_sales_details'] =  Saleslogs::select('salesman','team',Saleslogs::raw('count(salesman) as sales_count '))
                               ->whereBetween('create_at',[$start_range, $end_range])
                               ->groupBy('salesman','team')
                               ->get();
            return view('admin-dashboard',compact('result'));
        }

        else{
            //do nothing
        }
        
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
