<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;
use App\Models\Ytel;

class TvDashboardController extends Controller
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


        $result['today_total_calls'] = $this->find_total_call($todayDate_start,$todayDate_end);


        $result['todaycount'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])->count();
        $result['today_details'] = Saleslogs::select('salesman', 
                                Saleslogs::raw('SUM(downpay) as downpay_add'),
                                Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                Saleslogs::raw('SUM(retail) as retail_add'),
                                Saleslogs::raw('count(salesman) as sales_count '))
                                ->with('slaesagent')
                                ->whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                ->groupBy('salesman')
                                ->get();

        if($result['todaycount'] > 0){
            $result['today_details'] = json_encode($this->call_search_ytel($result['today_details']->toArray(),$todayDate_start,$todayDate_end));
        }

        $result['today_base_details'] = Saleslogs::select(Saleslogs::raw('SUM(cuscost) as cuscost'),
                                        Saleslogs::raw('SUM(finterm) as finterm'), 
                                        Saleslogs::raw('SUM(retail) as retail'))
                                        ->whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                        ->get()->toArray();


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
                                        ->with('slaesagent')
                                        ->whereBetween('purchdate',[$yesterdayDate_start, $yesterdayDate_start])
                                        ->groupBy('salesman')
                                        ->get();

        //dd($result['yesterday_details']->toArray());
        if($result['yesterdaycount'] > 0){
            $result['yesterday_details'] = json_encode($this->call_search_ytel($result['yesterday_details']->toArray(),$yesterdayDate_start,$yesterdayDate_start));
        }
        
        $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);


        $result['weekly_total_calls'] = $this->find_total_call($lastweek,$todayDate_end);
        
        $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->count();
        $result['weekly_details'] = Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add '),
                                    Saleslogs::raw('SUM(cuscost) as cuscost_add'), 
                                    Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                    Saleslogs::raw('SUM(retail) as retail_add'), 
                                    Saleslogs::raw('count(salesman) as sales_count '))
                                    ->with('slaesagent')
                                    ->whereBetween('purchdate',[$lastweek,$todayDate_end])
                                    ->groupBy('salesman')
                                    ->get();

        if($result['weeklycount'] > 0){
            $result['weekly_details'] = json_encode($this->call_search_ytel($result['weekly_details']->toArray(),$lastweek,$todayDate_end));
        }

        $result['weekly_base_details'] = Saleslogs::select(Saleslogs::raw('SUM(cuscost) as cuscost'),
                                        Saleslogs::raw('SUM(finterm) as finterm'), 
                                        Saleslogs::raw('SUM(retail) as retail'))
                                        ->whereBetween('purchdate',[$lastweek, $todayDate_end])
                                        ->get()->toArray();

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
                                    ->with('slaesagent')
                                    ->whereBetween('purchdate',[$Secondlastweek_start,$Secondlastweek_end])
                                    ->groupBy('salesman')
                                    ->get();

        if($result['Secondweeklycount'] > 0){
            $result['Secondweekly_details'] = json_encode($this->call_search_ytel($result['Secondweekly_details']->toArray(),$Secondlastweek_start,$Secondlastweek_end));
        }

        $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['Secondweeklycount']);

        $result['monthly_total_calls'] = $this->find_total_call($lastmonth,$todayDate_end);
        
        $result['monthlycount'] = Saleslogs::whereBetween('purchdate',[$lastmonth,$todayDate_end])->count();
        $result['monthly_details'] =  Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add'),
                                      Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                      Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                      Saleslogs::raw('SUM(retail) as retail_add'),
                                      Saleslogs::raw('count(salesman) as sales_count '))
                                      ->with('slaesagent')
                                      ->whereBetween('purchdate',[$lastmonth,$todayDate_end])
                                      ->groupBy('salesman')
                                      ->get();

        if($result['monthlycount'] > 0)
            $result['monthly_details'] = json_encode($this->call_search_ytel($result['monthly_details']->toArray(),$lastmonth,$todayDate_end));

        $result['monthly_base_details'] = Saleslogs::select(Saleslogs::raw('SUM(cuscost) as cuscost'),
                                          Saleslogs::raw('SUM(finterm) as finterm'), 
                                          Saleslogs::raw('SUM(retail) as retail'))
                                          ->whereBetween('purchdate',[$lastmonth, $todayDate_end])
                                          ->get()->toArray();

        $result['monthly_top'] = Saleslogs::select('salesman', Saleslogs::raw('count(salesman) as sales_count '))
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
                                      ->with('slaesagent')
                                      ->whereBetween('purchdate',[$Secondlastmonth_start,$Secondlastmonth_end])
                                      ->groupBy('salesman')
                                      ->get();

        if($result['Secondmonthlycount'] > 0)
            $result['Secondmonthly_details'] = json_encode($this->call_search_ytel($result['Secondmonthly_details']->toArray(),$Secondlastmonth_start,$Secondlastmonth_end));

        $result['monthlydata'] = $this->FlagSighCheck($result['monthlycount'], $result['Secondmonthlycount']);
        
        if($request->isMethod('get'))
        {
            $result['adv_range_flag'] = false;
            $result['adv_range_sales_count'] = '';
            $result['start_date'] = '';
            $result['end_date'] = '';
            $result['adv_range_sales_details'] = '';
            return view('tv-dashboard',compact('result'));
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
                                                  ->with('slaesagent')
                                                  ->whereBetween('purchdate',[$start_range, $end_range])
                                                  ->groupBy('salesman')
                                                  ->get();

            if($result['adv_range_sales_count'] > 0)
                $result['adv_range_sales_details'] = json_encode($this->call_search_ytel($result['adv_range_sales_details']->toArray(),$start_range,$end_range));

            return view('tv-dashboard',compact('result'));
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


    public function call_search_ytel($dataArr, $start_date, $end_date)
    {
    
        $resArr = array();
        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';
        // echo $start_range . "<br>";
        // echo $end_range . "<br>";
        // echo '<pre>';
        
        foreach ($dataArr as $datakey => $dataValue) {
            if(!empty($dataValue['slaesagent'])){

                // echo $dataValue['slaesagent']['user'];
                $result = Ytel::select('user',Ytel::raw('count(user) as total_calls'))
                          ->where('user','=',$dataValue['slaesagent']['user'])
                          ->where('list_id','999')
                          ->where('length_in_sec','>','15')
                          ->where('campaign_id','=','Sales')
                          ->whereBetween('call_date',[$start_range,$end_range])
                          ->groupBy('user')
                          ->get()->toArray();

                // print_r($result);
                if(!empty($result))
                    $dataValue['total_calls'] = $result[0]['total_calls'];

            }

            array_push($resArr,$dataValue);
            
        }
        //die();
        return $resArr;
    }

    public function find_total_call($start_date, $end_date)
    {

        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';
        
        
        $result = Ytel::where('list_id','999')
                  ->where('length_in_sec','>','15')
                  ->where('campaign_id','=','Sales')
                  ->whereBetween('call_date',[$start_range,$end_range])
                  ->count();

        // echo $result;
        // die();

        return $result;
    }
    
}
