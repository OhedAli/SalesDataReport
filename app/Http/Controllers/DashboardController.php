<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;
use App\Models\Ytel;

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
        $result['weekly_total_calls_daywise'] = $this->find_total_call($lastweek,$todayDate_end,$day_by_day=1);
        
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

        $result['calendar_data'] = json_encode(array_merge(($this->find_total_call($lastmonth,$todayDate_end,$day_by_day=1))->toArray(),($this->find_lead($lastmonth,$todayDate_end))->toArray()));

        
        
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

        // echo '<pre>';
        // print_r($result['monthly_details']->toArray());
        // die();                              

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
            return view('admin-dashboard',compact('result'));
        }

        elseif($request->isMethod('post'))
        {


            if($request->post('month') !== null){

                $full_date = $request->post('year').'-'.$request->post('month').'-1';
                $first_date = date('Y-m-1', strtotime($full_date));
                $last_date = date('Y-m-t', strtotime($full_date));

                $result['calendar_data'] = json_encode(array_merge(($this->find_total_call($first_date,$last_date,$day_by_day=1))->toArray(),($this->find_lead($first_date,$last_date))->toArray()));

                return $result['calendar_data'];
            }


            else{

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

                return view('admin-dashboard',compact('result'));
            }
            
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


    public function call_search_ytel($dataArr, $start_date, $end_date, $day_by_day=0)
    {
    
        $resArr = array();
        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';
        // echo $start_range . "<br>";
        // echo $end_range . "<br>";
        // echo '<pre>';

        if($day_by_day == 0){

            foreach ($dataArr as $datakey => $dataValue) {

                try{

                    if(!empty($dataValue['slaesagent'])){

                        // echo $dataValue['slaesagent']['user'];
                        $dataValue['total_calls'] = 0; 
                        foreach ($dataValue['slaesagent'] as $key => $agentvalue) {

                            
                            $result = Ytel::select('user',Ytel::raw('count(user) as total_calls'))
                                      ->where('user','=',$agentvalue['user'])
                                      ->where('list_id','999')
                                      ->where('length_in_sec','>','15')
                                      ->where('campaign_id','=','Sales')
                                      ->whereBetween('call_date',[$start_range,$end_range])
                                      ->groupBy('user')
                                      ->get()->toArray();

                            // print_r($result);
                            if(!empty($result))
                                $dataValue['total_calls'] = $dataValue['total_calls'] + $result[0]['total_calls'];
                        }

                    }

                    array_push($resArr,$dataValue);
                }
                
                catch(Exception $e){
                    echo 'Error'. $e->getMessage();
                }
                
            }
            //die();
            return $resArr;
        }
        
        else{

            foreach ($dataArr as $datakey => $dataValue) {

                try{

                    if(!empty($dataValue['slaesagent'])){

                        // echo $dataValue['slaesagent']['user'];
                        $result = array();
                        foreach ($dataValue['slaesagent'] as $key => $agentvalue) {

                            $res = Ytel::select(Ytel::raw('CAST(call_date AS DATE) as call_date'), Ytel::raw('count(call_date) as total_calls'))
                                      ->where('user','=',$agentvalue['user'])
                                      ->where('list_id','999')
                                      ->where('length_in_sec','>','15')
                                      ->where('campaign_id','=','Sales')
                                      ->whereBetween('call_date',[$start_range,$end_range])
                                      ->groupBy(Ytel::raw('CAST(call_date AS DATE)'))
                                      ->get()->toArray();

                            $result = array_merge($result,$res);
                        }
                    }

                    array_push($resArr,$result);
                }
                
                catch(Exception $e){
                    echo 'Error'. $e->getMessage();
                }
                
            }
            //die();
            return $resArr;
        }
        
    }

    public function find_total_call($start_date, $end_date, $day_by_day=0)
    {

        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';

        try{
        
            if($day_by_day == 0){

                $result = Ytel::where('list_id','999')
                      ->where('length_in_sec','>','15')
                      ->where('campaign_id','=','Sales')
                      ->whereBetween('call_date',[$start_range,$end_range])
                      ->count();

                // echo $result;
                // die();

                return $result;
            }
            else{

                $result = Ytel::select(Ytel::raw('CAST(call_date AS DATE) as call_date'), Ytel::raw('count(call_date) as total_calls'))
                          ->where('list_id','999')
                          ->where('length_in_sec','>','15')
                          ->where('campaign_id','=','Sales')
                          ->whereBetween('call_date',[$start_range,$end_range])
                          ->groupBy(Ytel::raw('CAST(call_date AS DATE)'))
                          ->get();

                // dd($result);
                return $result;
            }

        }

        catch(Exception $e){
            echo 'Error'. $e->getMessage();
        }
        
    }


    public function find_lead($stat_date, $end_date)
    {
        $lead_res = Saleslogs::select('purchdate', Saleslogs::raw('count(purchdate) as sales_count'))
                    ->whereBetween('purchdate',[$stat_date, $end_date])
                    ->groupBy('purchdate')
                    ->get();

        // dd($lead_res);
        return $lead_res;
    }


    public function salesman_details(Request $request, $name)
    {
        $sm_name = str_replace("-"," ",$name);
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

        $user_data = Saleslogs::select('salesman')
                     ->with('slaesagent')
                     ->where('salesman', '=', $sm_name)
                     ->distinct()
                     ->get()
                     ->toArray();

            $result['todaycount'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])->where('salesman',$sm_name)->count();

            $result['today_details'] =  Saleslogs::select('salesman',
                                        Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                        Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                        Saleslogs::raw('SUM(retail) as retail_add'))
                                        ->with('slaesagent')
                                        ->whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                        ->where('salesman',$sm_name)
                                        ->get();
            
            if($result['todaycount'] > 0){
                $result['today_details'] = $this->call_search_ytel($result['today_details']->toArray(),$todayDate_start,$todayDate_end);
                if(!array_key_exists('total_calls',$result['today_details'][0]))
                    $result['today_details'][0]['total_calls'] = 0;
            }
            else{
                $result['today_details'][0]['total_calls'] = 0;
            }

            $result['today_sales_data'] = Saleslogs::select('*')
                                          ->whereBetween('purchdate', [$todayDate_start, $todayDate_end])
                                          ->where('salesman',$sm_name)
                                          ->get();

            $result['today_oppurtunites'] = json_encode($this->find_not_sale($user_data, $todayDate_start, $todayDate_end),true);

            $result['yesterdaycount'] = Saleslogs::whereBetween('purchdate',[$yesterdayDate_start, $yesterdayDate_start])->where('salesman',$sm_name)->count();
            $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);
        
            $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->where('salesman',$sm_name)->count();

            $result['weekly_details'] = Saleslogs::select('salesman',
                                        Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                        Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                        Saleslogs::raw('SUM(retail) as retail_add'))
                                        ->with('slaesagent')
                                        ->whereBetween('purchdate',[$lastweek, $todayDate_end])
                                        ->where('salesman',$sm_name)
                                        ->get();

            if($result['weeklycount'] > 0){
                $result['weekly_details'] = $this->call_search_ytel($result['weekly_details']->toArray(),$lastweek,$todayDate_end);
                if(!array_key_exists('total_calls',$result['weekly_details'][0]))
                    $result['weekly_details'][0]['total_calls'] = 0;
            }
            else{
                $result['weekly_details'][0]['total_calls'] = 0;
            }

            $result['weekly_sales_data'] = Saleslogs::select('*')
                                          ->whereBetween('purchdate', [$lastweek, $todayDate_end])
                                          ->where('salesman',$sm_name)
                                          ->get();

            $result['weekly_oppurtunites'] = json_encode($this->find_not_sale($user_data, $lastweek, $todayDate_end),true);

            $result['Secondweeklycount'] = Saleslogs::whereBetween('purchdate',[$Secondlastweek_start,$Secondlastweek_end])->where('salesman',$sm_name)->count();
            $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['Secondweeklycount']);
        
            $result['monthlycount'] = Saleslogs::whereBetween('purchdate',[$lastmonth,$todayDate_end])->where('salesman',$sm_name)->count();


            $result['monthly_sm_details'] =  Saleslogs::select('salesman','purchdate',Saleslogs::raw('count(salesman) as sales_count '))
                                             ->whereBetween('purchdate',[$lastmonth, $todayDate_end])
                                             ->where('salesman',$sm_name)
                                             ->groupBy('salesman','purchdate')
                                             ->get();


            $monthly_data = Saleslogs::select('salesman',
                            Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                            Saleslogs::raw('SUM(finterm) as finterm_add'), 
                            Saleslogs::raw('SUM(retail) as retail_add'))
                            ->with('slaesagent')
                            ->whereBetween('purchdate',[$lastmonth, $todayDate_end])
                            ->where('salesman',$sm_name)
                            ->get();

            $result['calendar_data'] = json_encode(array_merge(($this->call_search_ytel($monthly_data->toArray(), $lastmonth, $todayDate_end,$day_by_day=1))[0],$result['monthly_sm_details']->toArray()));

            if($result['monthlycount'] > 0){
                $result['monthly_details'] = $this->call_search_ytel($monthly_data->toArray(),$lastmonth,$todayDate_end);
                if(!array_key_exists('total_calls',$result['monthly_details'][0]))
                    $result['monthly_details'][0]['total_calls'] = 0;
            }
            else{
                $result['monthly_details'] = $monthly_data->toArray();
                $result['monthly_details'][0]['total_calls'] = 0;
            }

            $result['monthly_sales_data'] = Saleslogs::select('*')
                                          ->whereBetween('purchdate', [$lastmonth, $todayDate_end])
                                          ->where('salesman',$sm_name)
                                          ->get();


            $result['monthly_oppurtunites'] = json_encode($this->find_not_sale($user_data, $lastmonth, $todayDate_end),true);

            // echo '<pre>';
            // print_r($result['monthly_oppurtunites']->toArray());
            // die();


            $result['Secondmonthlycount'] = Saleslogs::whereBetween('purchdate',[$Secondlastmonth_start,$Secondlastmonth_end])->where('salesman',$sm_name)->count();
            $result['monthlydata'] = $this->FlagSighCheck($result['monthlycount'], $result['Secondmonthlycount']);


            $result['adv_range_flag'] = false;
            $result['adv_range_sales_count'] = '';
            $result['start_date'] = '';
            $result['end_date'] = '';
            $result['prev_sales_details'] = '';
            $result['lead_info_details'] = '';
            $result['adv_range_oppurtunites'] = '';
            
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

                    $result['calendar_data'] = json_encode(array_merge(($this->call_search_ytel($monthly_data->toArray(), $first_date, $last_date,$day_by_day=1))[0],$result['prev_sales_details']->toArray()));
                    
                    return $result['calendar_data'];
                }

                elseif($request->post('leadDate') != ""){

                      // echo $request->post('leadDate');
                    $result['cal_date_data'] = array();

                    $caldate = $request->post('leadDate');
                    // $table_type = $request->post('tableType');

                    
                    $result['cal_date_data']['lead_info'] = Saleslogs::select('*')
                    ->where('purchdate', $caldate)
                    ->where('salesman',$sm_name)
                    ->get()
                    ->toArray();
                    
                    $result['cal_date_data']['opprt_info'] = $this->find_not_sale($user_data, $caldate, $caldate);

                    // print_r(json_encode($result['cal_date_data']));

                    return json_encode($result['cal_date_data'],true);
                }
                    
                else{
                    $result['adv_range_flag'] = true;
                    $start_range = $request->post('start_date');
                    $end_range = $request->post('end_date');

                    $result['start_date'] = date("d F, Y", strtotime($request->post('start_date')));
                    $result['end_date'] = date("d F, Y", strtotime($request->post('end_date')));
                    $result['adv_range_sales_count'] = Saleslogs::whereBetween('purchdate',[$start_range, $end_range])->where('salesman',$sm_name)->count();
                    $result['lead_info_details'] = Saleslogs::select('*')
                    ->whereBetween('purchdate', [$start_range, $end_range])
                    ->where('salesman',$sm_name)
                    ->get();

                    $result['adv_range_oppurtunites'] = json_encode($this->find_not_sale($user_data, $start_range, $end_range),true);

                    return view('salesman-details',compact('result'));
                }

                
            }

            else{
                //do nothing
            }
        
    }

    public function find_not_sale($sm_data , $start_date, $end_date)
    {
        // echo '<pre>';
        // print_r($sm_data);
        $result_opprt = array();
        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';
        
        

        foreach ($sm_data[0]['slaesagent'] as $key => $agentvalue) {
            $user_id = $agentvalue['user'];

            $res_data = DB::connection('mysql3')->table('vicidial_closer_log')
                    ->join('vicidial_list',function($join) use($user_id,$start_range,$end_range) {
                        $join->on('vicidial_list.lead_id','=','vicidial_closer_log.lead_id')
                        ->where('vicidial_list.security_phrase','!=', 'Sales')
                        ->where('vicidial_closer_log.list_id','999')
                        ->where('vicidial_closer_log.length_in_sec','>','420')
                        ->where('vicidial_closer_log.campaign_id','=','Sales')
                        // ->where('vicidial_list.user','=','vicidial_closer_log.user')
                        ->where('vicidial_closer_log.user','=',$user_id)
                        ->whereBetween('vicidial_closer_log.call_date',[$start_range,$end_range]);
                    })
                    ->get()
                    ->toArray();

            $result_opprt = array_merge($result_opprt,$res_data);
        }


        return $result_opprt;

    }
      
}
