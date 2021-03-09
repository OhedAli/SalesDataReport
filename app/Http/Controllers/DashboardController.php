<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;


class DashboardController extends Controller
{
    public function index(Request $request){

        $result = array();

        date_default_timezone_set("America/Chicago");

        if(date('D') != 'Mon'){
            $lastweek = date('Y-m-d', strtotime('last Monday'));
            $Secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
            $Secondlastweek_start = date('Y-m-d', strtotime('-7 days', strtotime($Secondlastweek_end)));
        }
        else{
            $lastweek = date('Y-m-d');
            $Secondlastweek_start = date('Y-m-d', strtotime('last Monday'));
            $Secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
        }
        
        $lastmonth = date('Y-m-01');
        $days_no_prev_mnth = date('t',mktime(0,0,0, date('n') -1 ));
        $Secondlastmonth_start = date('Y-m-d', strtotime('-'.$days_no_prev_mnth.' days', strtotime($lastmonth)));
        $Secondlastmonth_end = date('Y-m-d', strtotime('-1 days', strtotime($lastmonth)));

        $todayDate_start = date('Y-m-d');
        $yesterdayDate_start = date('Y-m-d', strtotime('-1 days')); 
        $todayDate_end = date('Y-m-d');


        $result['todaycount'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])->count();
        $result['today_details'] = Saleslogs::select('salesman', 
                                Saleslogs::raw('SUM(downpay) as downpay_add'),
                                Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                Saleslogs::raw('SUM(retail) as retail_add'),
                                Saleslogs::raw('count(salesman) as sales_count '))
                                ->whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                ->groupBy('salesman')
                                ->get();
        $result['today_top'] = Saleslogs::select('salesman', Saleslogs::raw('count(salesman) as sales_count '))
                                ->whereBetween('purchdate',[$todayDate_start,$todayDate_end])
                                ->groupBy('salesman')
                                ->orderBy('sales_count','desc')
                                ->limit(3)
                                ->get();
        //dd($result['today_top']);
        $result['yesterdaycount'] = Saleslogs::whereBetween('purchdate',[$yesterdayDate_start, $yesterdayDate_start])->count();
        $result['yesterday_details'] = Saleslogs::select('salesman', 
                                Saleslogs::raw('SUM(downpay) as downpay_add'),
                                Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                Saleslogs::raw('SUM(retail) as retail_add'),
                                Saleslogs::raw('count(salesman) as sales_count '))
                                ->whereBetween('purchdate',[$yesterdayDate_start, $yesterdayDate_start])
                                ->groupBy('salesman')
                                ->get();
        
        $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);
        
        $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->count();
        $result['weekly_details'] = Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add '),
                                    Saleslogs::raw('SUM(cuscost) as cuscost_add'), 
                                    Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                    Saleslogs::raw('SUM(retail) as retail_add'), 
                                    Saleslogs::raw('count(salesman) as sales_count '))
                                    ->whereBetween('purchdate',[$lastweek,$todayDate_end])
                                    ->groupBy('salesman')
                                    ->get();

        //$result['weekly_details'] = $this->call_search_ytel($result['weekly_details']->toArray());

        // echo '<pre>';
        // print_r($result['weekly_details']);
        // die();

        $result['weekly_top'] = Saleslogs::select('salesman', Saleslogs::raw('count(salesman) as sales_count '))
                                ->whereBetween('purchdate',[$lastweek,$todayDate_end])
                                ->groupBy('salesman')
                                ->orderBy('sales_count','desc')
                                ->limit(3)
                                ->get();

        $result['Secondweeklycount'] = Saleslogs::whereBetween('purchdate',[$Secondlastweek_start,$Secondlastweek_end])->count();
        $result['Secondweekly_details'] = Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add '),
                                    Saleslogs::raw('SUM(cuscost) as cuscost_add'), 
                                    Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                    Saleslogs::raw('SUM(retail) as retail_add'), 
                                    Saleslogs::raw('count(salesman) as sales_count '))
                                    ->whereBetween('purchdate',[$Secondlastweek_start,$Secondlastweek_end])
                                    ->groupBy('salesman')
                                    ->get();
        $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['Secondweeklycount']);
        
        $result['monthlycount'] = Saleslogs::whereBetween('purchdate',[$lastmonth,$todayDate_end])->count();
        $result['monthly_details'] =  Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add'),
                                      Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                      Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                      Saleslogs::raw('SUM(retail) as retail_add'),
                                      Saleslogs::raw('count(salesman) as sales_count '))
                                      ->whereBetween('purchdate',[$lastmonth,$todayDate_end])
                                      ->groupBy('salesman')
                                      ->get();

        $result['montly_top'] = Saleslogs::select('salesman', Saleslogs::raw('count(salesman) as sales_count '))
                                ->whereBetween('purchdate',[$lastmonth,$todayDate_end])
                                ->groupBy('salesman')
                                ->orderBy('sales_count','desc') 
                                ->limit(3)
                                ->get();
        //echo '<pre>';
        //print_r($result['montly_top']);

        $result['Secondmonthlycount'] = Saleslogs::whereBetween('purchdate',[$Secondlastmonth_start,$Secondlastmonth_end])->count();
        $result['Secondmonthly_details'] =  Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add'),
                                      Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                      Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                      Saleslogs::raw('SUM(retail) as retail_add'),
                                      Saleslogs::raw('count(salesman) as sales_count '))
                                      ->whereBetween('purchdate',[$Secondlastmonth_start,$Secondlastmonth_end])
                                      ->groupBy('salesman')
                                      ->get();
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
            $result['adv_range_sales_count'] = Saleslogs::whereBetween('purchdate',[$start_range, $end_range])->count();
            $result['adv_range_sales_details'] =  Saleslogs::select('salesman',
                                      Saleslogs::raw('SUM(downpay) as downpay_add'),
                                      Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                      Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                      Saleslogs::raw('SUM(retail) as retail_add'),
                                      Saleslogs::raw('count(salesman) as sales_count '))
                               ->whereBetween('purchdate',[$start_range, $end_range])
                               ->groupBy('salesman')
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


    public function call_search_ytel($dataArr)
    {
    
        $resArr = array();
        foreach ($dataArr as $datakey => $dataValue) {
        $name = $dataValue['salesman'];
        $start_date = '2021-03-08 00:00:00';
        $end_date = date('Y-m-d H:i:s');
        $total_call = DB::connection('mysql3')->table('vicidial_closer_log')
                ->join('vicidial_users',function($join1) use($name,$start_date,$end_date) {
                $join1->on('vicidial_users.user','=','vicidial_closer_log.user')
                ->where('vicidial_users.full_name', $name)
                ->where('vicidial_closer_log.list_id','999')
                ->where('vicidial_closer_log.length_in_sec','>','15')
                ->whereBetween('vicidial_closer_log.call_date',[$start_date,$end_date]);
                })
                ->join('vicidial_list',function($join2) use($name,$start_date,$end_date) {
                $join2->on('vicidial_list.user','=','vicidial_closer_log.user')
                      ->on('vicidial_list.lead_id','=','vicidial_closer_log.lead_id')
                      ->on('vicidial_list.entry_date','=','vicidial_closer_log.call_date');

                })
                ->count();
                $dataValue['total_calls'] = $total_call;
                array_push($resArr,$dataValue);
        }
        return $resArr;
    }


    public function salesman_details(Request $request, $name)
    {
        $sm_name = str_replace("_"," ",$name);
        $result = array();
        $result['sm_name'] = $sm_name;
        date_default_timezone_set("America/Chicago");

        if(date('D') != 'Mon'){
            $lastweek = date('Y-m-d', strtotime('last Monday'));
            $Secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
            $Secondlastweek_start = date('Y-m-d', strtotime('-7 days', strtotime($Secondlastweek_end)));
        }
        else{
            $lastweek = date('Y-m-d');
            $Secondlastweek_start = date('Y-m-d', strtotime('last Monday'));
            $Secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
        }
           
        $lastmonth = date('Y-m-01');
        $days_no_prev_mnth = date('t',mktime(0,0,0, date('n') -1 ));
        $Secondlastmonth_start = date('Y-m-d', strtotime('-'.$days_no_prev_mnth.' days', strtotime($lastmonth)));
        $Secondlastmonth_end = date('Y-m-d', strtotime('-1 days', strtotime($lastmonth)));
        
        $todayDate_start = date('Y-m-d'); 
        $yesterdayDate_start = date('Y-m-d', strtotime('-1 days')); 
        $todayDate_end = date('Y-m-d');

            $result['todaycount'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])->where('salesman',$sm_name)->count();
            $result['yesterdaycount'] = Saleslogs::whereBetween('purchdate',[$yesterdayDate_start, $todayDate_start])->where('salesman',$sm_name)->count();
            $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);
        
            $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->where('salesman',$sm_name)->count();
            $result['Secondweeklycount'] = Saleslogs::whereBetween('purchdate',[$Secondlastweek_start,$Secondlastweek_end])->where('salesman',$sm_name)->count();
            $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['Secondweeklycount']);
        
            $result['monthlycount'] = Saleslogs::whereBetween('purchdate',[$lastmonth,$todayDate_end])->where('salesman',$sm_name)->count();
            $result['Secondmonthlycount'] = Saleslogs::whereBetween('purchdate',[$Secondlastmonth_start,$Secondlastmonth_end])->where('salesman',$sm_name)->count();
            $result['monthlydata'] = $this->FlagSighCheck($result['monthlycount'], $result['Secondmonthlycount']);

            $result['monthly_sm_details'] =  Saleslogs::select('salesman','purchdate',Saleslogs::raw('count(salesman) as sales_count '))
                                             ->whereBetween('purchdate',[$lastmonth, $todayDate_end])
                                             ->where('salesman',$sm_name)
                                             ->groupBy('salesman','purchdate')
                                             ->get();

            $result['adv_range_flag'] = false;
            $result['adv_range_sales_count'] = '';
            $result['start_date'] = '';
            $result['end_date'] = '';
            $result['prev_sales_details'] = '';
            
            
            if($request->isMethod('get'))
            {
                return view('salesman-details',compact('result'));
            }

            elseif($request->isMethod('post'))
            {
                //echo $request->post('month');
                //die();
                if($request->post('month') !== null){

                    $full_date = $request->post('year').'-'.$request->post('month').'-1';
                    $first_date = date('Y-m-1', strtotime($full_date));
                    $last_date = date('Y-m-t', strtotime($full_date));

                    $result['prev_sales_details'] = Saleslogs::select('salesman','purchdate',Saleslogs::raw('count(salesman) as sales_count '))
                                                    ->whereBetween('purchdate',[$first_date, $last_date])
                                                    ->where('salesman',$sm_name)
                                                    ->groupBy('salesman','purchdate')
                                                    ->get();
                    return $result['prev_sales_details'];
                }
                    
                else{
                    $result['adv_range_flag'] = true;
                    $start_range = $request->post('start_date');
                    $end_range = $request->post('end_date');

                    $result['start_date'] = date("dS F, Y", strtotime($request->post('start_date')));
                    $result['end_date'] = date("dS F, Y", strtotime($request->post('end_date')));
                    $result['adv_range_sales_count'] = Saleslogs::whereBetween('purchdate',[$start_range, $end_range])->where('salesman',$sm_name)->count();
                    return view('salesman-details',compact('result'));
                }

                
            }

            else{
                //do nothing
            }
        
    }
      
}
