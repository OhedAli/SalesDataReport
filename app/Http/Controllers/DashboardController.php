<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;
use App\Models\Salescalls;
use App\Models\Cancellogs;
use App\Models\Ytel;

class DashboardController extends Controller
{
    public function index(Request $request){

        $result = array();

        date_default_timezone_set("America/Chicago");

        if(date('D') != 'Mon'){
            $lastweek = date('Y-m-d', strtotime('last Monday'));
            $secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
            $secondlastweek_start = date('Y-m-d', strtotime('-7 days', strtotime($secondlastweek_end)));
        }
        else{
            $lastweek = date('Y-m-d');
            $secondlastweek_start = date('Y-m-d', strtotime('last Monday'));
            $secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
        }
        
        $lastmonth = date('Y-m-01');
        $days_no_prev_mnth = date('t',mktime(0,0,0, date('n') -1 ));
        $secondlastmonth_start = date('Y-m-d', strtotime('-'.$days_no_prev_mnth.' days', strtotime($lastmonth)));
        $secondlastmonth_end = date('Y-m-d', strtotime('-1 days', strtotime($lastmonth)));

        $todayDate_start = date('Y-m-d');
        $yesterdayDate_start = date('Y-m-d', strtotime('-1 days')); 
        $todayDate_end = date('Y-m-d');

        $result['today_total_calls'] = $this->find_total_call($todayDate_start,$todayDate_end);


        $result['todaycount'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])->count();
        $result['today_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                            ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])
                                            ->count();

        $result['today_cancel_count'] = Cancellogs::whereBetween('CanDate',[$todayDate_start, $todayDate_end])->count();

        $result['today_details'] = Saleslogs::select('salesman', 'users.avatar',
                                Saleslogs::raw('SUM(downpay) as downpay_add'),
                                Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                Saleslogs::raw('SUM(retail) as retail_add'),
                                Saleslogs::raw('count(salesman) as sales_count '))
                                ->with('slaesagent')
                                ->leftJoin('vsctools_autoprotect.users as users','salesman','=','users.name')
                                ->whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                ->groupBy('salesman')
                                ->get();


        $result['today_top'] = json_encode(array());
        $topper_sales_count = array();
        $topper_total_calls = array();

        if($result['todaycount'] > 0){
            $res_today = $this->call_search_ytel($result['today_details']->toArray(),$todayDate_start,$todayDate_end);
            $result['today_details'] = json_encode($res_today,true);

            foreach ($res_today as $key => $res_today_top) {
                $topper_sales_count[$key] = $res_today_top['sales_count'];
                $topper_total_calls[$key] = $res_today_top['total_calls'];
            }

            array_multisort($topper_sales_count,SORT_DESC,$topper_total_calls,SORT_ASC,$res_today);
            $result['today_top'] = json_encode(array_slice($res_today, 0,10),true);
        }


        $result['today_top_team'] = Saleslogs::select('team', Saleslogs::raw('count(team) as team_deal_count '))
                                    ->whereBetween('purchdate',[$todayDate_start,$todayDate_end])
                                    ->where('team','!=','')
                                    ->groupBy('team')
                                    ->orderBy('team_deal_count','desc')
                                    ->limit(4)
                                    ->get();


        $result['today_base_details'] = $this->get_base_details($todayDate_start,$todayDate_end);


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

        $result['yesterday_base_details'] = $this->get_base_details($yesterdayDate_start, $yesterdayDate_start);
        $result['yesterday_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$yesterdayDate_start, $yesterdayDate_start])
                                            	->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])
                                            	->count();

        $result['yesterday_total_calls'] = $this->find_total_call($yesterdayDate_start, $yesterdayDate_start);

        $result['yesterday_cancel_count'] = Cancellogs::whereBetween('CanDate',
                                            [$yesterdayDate_start, $yesterdayDate_start])->count();

        // dd($result['yesterday_details']->toArray());
        if($result['yesterdaycount'] > 0){
            $result['yesterday_details'] = json_encode($this->call_search_ytel($result['yesterday_details']->toArray(),$yesterdayDate_start,$yesterdayDate_start));
        }
        
        $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);


        $result['weekly_total_calls'] = $this->find_total_call($lastweek,$todayDate_end);
        $result['weekly_total_calls_daywise'] = $this->find_total_call($lastweek,$todayDate_end,$day_by_day=1);
        
        $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->count();
        $result['weekly_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$lastweek, $todayDate_end])
                                             ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])
                                             ->count();

        $result['weekly_cancel_count'] = Cancellogs::whereBetween('CanDate',[$lastweek, $todayDate_end])->count();

        $result['weekly_details'] = Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add '),'users.avatar',
                                    Saleslogs::raw('SUM(cuscost) as cuscost_add'), 
                                    Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                    Saleslogs::raw('SUM(retail) as retail_add'), 
                                    Saleslogs::raw('count(salesman) as sales_count '))
                                    ->with('slaesagent')
                                    ->leftJoin('vsctools_autoprotect.users as users','salesman','=','users.name')
                                    ->whereBetween('purchdate',[$lastweek,$todayDate_end])
                                    ->groupBy('salesman')
                                    ->get();                             
                            
        $result['weekly_top'] = json_encode(array());
        $topper_sales_count = array();
        $topper_total_calls = array();

        if($result['weeklycount'] > 0){
            $res_weekly = $this->call_search_ytel($result['weekly_details']->toArray(),$lastweek,$todayDate_end);
            $result['weekly_details'] = json_encode($res_weekly,true);

            foreach ($res_weekly as $key => $res_weekly_top) {
                $topper_sales_count[$key] = $res_weekly_top['sales_count'];
                $topper_total_calls[$key] = $res_weekly_top['total_calls'];
            }

            array_multisort($topper_sales_count,SORT_DESC,$topper_total_calls,SORT_ASC,$res_weekly);
            $result['weekly_top'] = json_encode(array_slice($res_weekly, 0,10),true);

        }

        $result['weekly_top_team'] = Saleslogs::select('team', Saleslogs::raw('count(team) as team_deal_count '))
                                      ->whereBetween('purchdate',[$lastweek,$todayDate_end])
                                      ->where('team','!=','')
                                      ->groupBy('team')
                                      ->orderBy('team_deal_count','desc')
                                      ->limit(4)
                                      ->get();

        $result['weekly_base_details'] = $this->get_base_details($lastweek,$todayDate_end);


        $result['secondweeklycount'] = Saleslogs::whereBetween('purchdate',[$secondlastweek_start,$secondlastweek_end])->count();
        $result['secondweekly_details'] = Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add '),
                                    Saleslogs::raw('SUM(cuscost) as cuscost_add'), 
                                    Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                    Saleslogs::raw('SUM(retail) as retail_add'), 
                                    Saleslogs::raw('count(salesman) as sales_count '))
                                    ->with('slaesagent')
                                    ->whereBetween('purchdate',[$secondlastweek_start,$secondlastweek_end])
                                    ->groupBy('salesman')
                                    ->get();

        $result['secondweekly_base_details'] = $this->get_base_details($secondlastweek_start,$secondlastweek_end);
        $result['secondweekly_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$secondlastweek_start,$secondlastweek_end]										  )->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])
                                            	   ->count();

        $result['secondweekly_total_calls'] = $this->find_total_call($secondlastweek_start,$secondlastweek_end);

        $result['secondweekly_cancel_count'] = Cancellogs::whereBetween('CanDate',
                                               [$secondlastweek_start, $secondlastweek_end])->count();

        if($result['secondweeklycount'] > 0){
            $result['secondweekly_details'] = json_encode($this->call_search_ytel($result['secondweekly_details']->toArray(),$secondlastweek_start,$secondlastweek_end));
        }

        $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['secondweeklycount']);

        $result['monthly_total_calls'] = $this->find_total_call($lastmonth,$todayDate_end);

        $result['calendar_data'] = json_encode(array_merge(($this->find_total_call($lastmonth,$todayDate_end,$day_by_day=1))->toArray(),($this->find_lead($lastmonth,$todayDate_end))->toArray(),($this->cancel_lead($lastmonth,$todayDate_end))->toArray()));

        // echo '<pre>';
        // print_r(json_decode($result['calendar_data']));
        // die();
        
        
        $result['monthlycount'] = Saleslogs::whereBetween('purchdate',[$lastmonth,$todayDate_end])->count();
        $result['monthly_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$lastmonth, $todayDate_end])
                                             ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])
                                             ->count();

        $result['monthly_cancel_count'] = Cancellogs::whereBetween('CanDate',[$lastmonth, $todayDate_end])->count();

        $result['monthly_details'] =  Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add'), 'users.avatar',
                                      Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                      Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                      Saleslogs::raw('SUM(retail) as retail_add'),
                                      Saleslogs::raw('count(salesman) as sales_count '))
                                      ->with('slaesagent')
                                      ->leftJoin('vsctools_autoprotect.users as users','salesman','=','users.name')
                                      ->whereBetween('purchdate',[$lastmonth,$todayDate_end])
                                      ->groupBy('salesman')
                                      ->get();
        

        $result['monthly_top'] = json_encode(array());
        $topper_sales_count = array();
        $topper_total_calls = array();

        if($result['monthlycount'] > 0){

            $res_monthly = $this->call_search_ytel($result['monthly_details']->toArray(),$lastmonth,$todayDate_end);
            $result['monthly_details'] = json_encode($res_monthly,true);

            foreach ($res_monthly as $key => $res_monthly_top) {
                $topper_sales_count[$key] = $res_monthly_top['sales_count'];
                $topper_total_calls[$key] = $res_monthly_top['total_calls'];
            }

            array_multisort($topper_sales_count,SORT_DESC,$topper_total_calls,SORT_ASC,$res_monthly);
            $result['monthly_top'] = json_encode(array_slice($res_monthly, 0,10),true);
        }


        $result['monthly_top_team'] = Saleslogs::select('team', Saleslogs::raw('count(team) as team_deal_count '))
                                      ->whereBetween('purchdate',[$lastmonth,$todayDate_end])
                                      ->where('team','!=','')
                                      ->groupBy('team')
                                      ->orderBy('team_deal_count','desc')
                                      ->limit(4)
                                      ->get();

        $result['monthly_base_details'] = $this->get_base_details($lastmonth,$todayDate_end);

        // echo '<pre>';
        // print_r($result['monthly_top_team']->toArray());
        // die();

        $result['secondmonthlycount'] = Saleslogs::whereBetween('purchdate',[$secondlastmonth_start,$secondlastmonth_end])->count();
        $result['secondmonthly_details'] =  Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add'),
                                      Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                      Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                      Saleslogs::raw('SUM(retail) as retail_add'),
                                      Saleslogs::raw('count(salesman) as sales_count '))
                                      ->with('slaesagent')
                                      ->whereBetween('purchdate',[$secondlastmonth_start,$secondlastmonth_end])
                                      ->groupBy('salesman')
                                      ->get();

        $result['secondmonthly_base_details'] = $this->get_base_details($secondlastmonth_start,$secondlastmonth_end);
        $result['secondmonthly_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$secondlastmonth_start,$secondlastmonth_end])->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])->count();

        $result['secondmonthly_total_calls'] = $this->find_total_call($secondlastmonth_start,$secondlastmonth_end);

        $result['secondmonthly_cancel_count'] = Cancellogs::whereBetween('CanDate',
                                                [$secondlastmonth_start, $secondlastmonth_end])->count();

        if($result['secondmonthlycount'] > 0)
            $result['secondmonthly_details'] = json_encode($this->call_search_ytel($result['secondmonthly_details']->toArray(),$secondlastmonth_start,$secondlastmonth_end));

        $result['monthlydata'] = $this->FlagSighCheck($result['monthlycount'], $result['secondmonthlycount']);
        
        if($request->isMethod('get'))
        {
            $result['adv_range_flag'] = false;
            return view('admin-dashboard',compact('result'));
        }

        elseif($request->isMethod('post'))
        {


            if($request->post('month') !== null){

                $full_date = $request->post('year').'-'.$request->post('month').'-1';
                $first_date = date('Y-m-1', strtotime($full_date));
                $last_date = date('Y-m-t', strtotime($full_date));

                $result['calendar_data'] = json_encode(array_merge(($this->find_total_call($first_date,$last_date,$day_by_day=1))->toArray(),($this->find_lead($first_date,$last_date))->toArray(),($this->cancel_lead($first_date,$last_date))->toArray()));

                return $result['calendar_data'];
            }

     
            else{

                $result['adv_range_flag'] = true;
                $start_range = $request->post('start_date');
                $end_range = $request->post('end_date');
                $result['start_date'] = date("dS F, Y", strtotime($request->post('start_date')));
                $result['end_date'] = date("dS F, Y", strtotime($request->post('end_date')));
                $result['adv_range_sales_count'] = Saleslogs::whereBetween('purchdate',[$start_range, $end_range])->count();
                $result['adv_range_cancel_count'] = Cancellogs::whereBetween('CanDate',[$start_range, $end_range])->count();
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

                $result['adv_range_base_details'] = $this->get_base_details($start_range, $end_range);
        		$result['adv_range_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$start_range, $end_range])
                                             ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'])
                                             ->count();
                $result['adv_range_total_calls'] = $this->find_total_call($start_range, $end_range);


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


    public function get_base_details($start_date,$end_date)
    {
    	$base_details = Saleslogs::select(Saleslogs::raw('SUM(cuscost) as cuscost'),
                        Saleslogs::raw('SUM(finterm) as finterm'), 
                        Saleslogs::raw('SUM(retail) as retail'))
                        ->whereBetween('purchdate',[$start_date, $end_date])
                        ->get();

        return $base_details;
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

                            
                            $sub_res = Salescalls::select('user','phone_number')
                                      ->where('user','=',$agentvalue['user'])
                                      ->where('list_id','999')
                                      ->where('length_in_sec','>','20')
                                      ->where('campaign_id','=','Sales')
                                      ->whereBetween('call_date',[$start_range,$end_range])
                                      // ->groupBy('user')
                                      ->distinct('phone_number');
                                      // ->get()->toArray();

                            $result = DB::connection('mysql2')
			                		  ->table( DB::raw("({$sub_res->toSql()}) as sub_res") )
			                		  ->select('user',DB::raw('count(user) as total_calls'))
								      ->mergeBindings($sub_res->getQuery())
								      ->groupBy('user')
								      ->get()->toArray();

							/*echo '<pre>';
                            print_r($result);
                            die();*/

                            if(!empty($result))
                                $dataValue['total_calls'] = $dataValue['total_calls'] + $result[0]->total_calls;
                        }

                    }

                    else{
                        $dataValue['total_calls'] = 0;
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

                        $result = array();
                        foreach ($dataValue['slaesagent'] as $key => $agentvalue) {
                            // echo $agentvalue['user']."<br>";
                            $sub_res = Salescalls::select(Salescalls::raw('CAST(call_date AS DATE) as call_date'),'phone_number')
                                      ->where('user','=',$agentvalue['user'])
                                      ->where('list_id','999')
                                      ->where('length_in_sec','>','20')
                                      ->where('campaign_id','=','Sales')
                                      ->whereBetween('call_date',[$start_range,$end_range])
                                      // ->groupBy(Salescalls::raw('CAST(call_date AS DATE)'))
                                      ->distinct('phone_number');
                                      // ->get()->toArray();

                            $res = 	DB::connection('mysql2')
		                		  	->table( DB::raw("({$sub_res->toSql()}) as sub_res") )
		                		  	->select('call_date',DB::raw('count(call_date) as total_calls'))
							      	->mergeBindings($sub_res->getQuery())
							      	->groupBy('call_date')
							      	->get()->toArray();

							
                            $result = array_merge($result,json_decode(json_encode($res,true),true));
                        }

                        array_push($resArr,$result);
                    }

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

                $result = Salescalls::where('list_id','999')
                      ->where('length_in_sec','>','20')
                      ->where('campaign_id','=','Sales')
                      ->whereBetween('call_date',[$start_range,$end_range])
                      ->distinct('phone_number')
                      ->count();
                
                // echo '<pre>';
                // print_r($result);
                // die();

                return $result;
            }
            else{

                $sub_res = Salescalls::select(Salescalls::raw('CAST(call_date AS DATE) as call_date'),'phone_number')
                		  ->where('list_id','999')
                          ->where('length_in_sec','>','20')
                          ->where('campaign_id','=','Sales')
                          ->whereBetween('call_date',[$start_range,$end_range])
                          ->distinct('phone_number');

                $result = DB::connection('mysql2')
                		  ->table( DB::raw("({$sub_res->toSql()}) as sub_res") )
                		  ->select('call_date',DB::raw('count(call_date) as total_calls'))
					      ->mergeBindings($sub_res->getQuery())
					      ->groupBy('call_date')
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

    public function cancel_lead($stat_date, $end_date)
    {
        $cancel_lead_res = Cancellogs::select('CanDate', Saleslogs::raw('count(CanDate) as cancel_count'))
                           ->whereBetween('CanDate',[$stat_date, $end_date])
                           ->groupBy('CanDate')
                           ->get();

        return $cancel_lead_res;

    }

    public function sm_cancel_lead($stat_date, $end_date,$sm_name)
    {
        $cancel_lead_res = Cancellogs::select('CanDate', Saleslogs::raw('count(CanDate) as cancel_count'))
                           ->whereBetween('CanDate',[$stat_date, $end_date])
                           ->where('salesman','=',$sm_name)
                           ->groupBy('CanDate')
                           ->get();

        return $cancel_lead_res;

    }



    public function salesman_details(Request $request, $name)
    {
        $sm_name = str_replace("-"," ",$name);
        $sm_name = str_replace("_","-",$sm_name);
        
        $result = array();
        $result['sm_name'] = $sm_name;
        date_default_timezone_set("America/Chicago");

        if(date('D') != 'Mon'){
            $lastweek = date('Y-m-d', strtotime('last Monday'));
            $secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
            $secondlastweek_start = date('Y-m-d', strtotime('-7 days', strtotime($secondlastweek_end)));
        }
        else{
            $lastweek = date('Y-m-d');
            $secondlastweek_start = date('Y-m-d', strtotime('last Monday'));
            $secondlastweek_end = date('Y-m-d', strtotime('-1 days', strtotime($lastweek)));
        }
           
        $lastmonth = date('Y-m-01');
        $days_no_prev_mnth = date('t',mktime(0,0,0, date('n') -1 ));
        $secondlastmonth_start = date('Y-m-d', strtotime('-'.$days_no_prev_mnth.' days', strtotime($lastmonth)));
        $secondlastmonth_end = date('Y-m-d', strtotime('-1 days', strtotime($lastmonth)));
        
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

            $result['today_cancel_sm_count'] = Cancellogs::whereBetween('CanDate',[$todayDate_start, $todayDate_end])
                                               ->where('salesman',$sm_name)
                                               ->count();

            $result['today_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                                ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND'])
                                                ->where('salesman',$sm_name)
                                                ->count();

            $result['today_details'] =  Saleslogs::select('salesman',
                                        Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                        Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                        Saleslogs::raw('SUM(retail) as retail_add'))
                                        ->with('slaesagent')
                                        ->whereBetween('purchdate',[$todayDate_start, $todayDate_end])
                                        ->where('salesman',$sm_name)
                                        ->get();

            $today_sales_data = Saleslogs::select('*',Saleslogs::raw("regexp_replace(phone, '[^0-9]', '') as phone_no"))
                                  ->whereBetween('purchdate', [$todayDate_start, $todayDate_end])
                                  ->where('salesman',$sm_name)
                                  ->get()
                                  ->toArray();   



            $today_cancel_data =  Cancellogs::select('*',
            					  Cancellogs::raw("regexp_replace(custPhone, '[^0-9]', '') as phone_no"))
                                  ->whereBetween('CanDate', [$todayDate_start, $todayDate_end])
                                  ->where('salesman',$sm_name)
                                  ->get()
                                  ->toArray();

            $result['today_cancel_data'] = json_encode($this->fetch_recording_path($user_data,$today_cancel_data),true);

            
            if($result['todaycount'] > 0){
                $result['today_details'] = $this->call_search_ytel($result['today_details']->toArray(),$todayDate_start,$todayDate_end);
                if(!array_key_exists('total_calls',$result['today_details'][0]))
                    $result['today_details'][0]['total_calls'] = 0;

                $result['today_sales_data'] = json_encode($this->fetch_recording_path($user_data,$today_sales_data),true);
            }
            else{
                $result['today_details'][0]['total_calls'] = 0;
                $result['today_sales_data'] = json_encode($today_sales_data,true);
            }                          

            $result['today_oppurtunites'] = json_encode($this->find_not_sale($user_data, $today_sales_data, $todayDate_start, $todayDate_end),true);

            $result['yesterdaycount'] = Saleslogs::whereBetween('purchdate',[$yesterdayDate_start, $yesterdayDate_start])->where('salesman',$sm_name)->count();
            $result['dailydata'] = $this->FlagSighCheck($result['todaycount'], $result['yesterdaycount']);
        
            $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->where('salesman',$sm_name)->count();

            $result['weekly_cancel_sm_count'] = Cancellogs::whereBetween('CanDate',[$lastweek, $todayDate_end])
                                               ->where('salesman',$sm_name)
                                               ->count();

            $result['weekly_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$lastweek, $todayDate_end])
                                                 ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND'])
                                                 ->where('salesman',$sm_name)
                                                 ->count();

            $result['weekly_details'] = Saleslogs::select('salesman',
                                        Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                                        Saleslogs::raw('SUM(finterm) as finterm_add'), 
                                        Saleslogs::raw('SUM(retail) as retail_add'))
                                        ->with('slaesagent')
                                        ->whereBetween('purchdate',[$lastweek, $todayDate_end])
                                        ->where('salesman',$sm_name)
                                        ->get();


            $weekly_sales_data =  Saleslogs::select('*',Saleslogs::raw("regexp_replace(phone, '[^0-9]', '') as phone_no"))
                                  ->whereBetween('purchdate', [$lastweek, $todayDate_end])
                                  ->where('salesman',$sm_name)
                                  ->get()
                                  ->toArray();


            $weekly_cancel_data =  Cancellogs::select('*',
            					   Cancellogs::raw("regexp_replace(custPhone, '[^0-9]', '') as phone_no"))
                                   ->whereBetween('CanDate', [$lastweek, $todayDate_end])
                                   ->where('salesman',$sm_name)
                                   ->get()
                                   ->toArray();

            $result['weekly_cancel_data'] = json_encode($this->fetch_recording_path($user_data,$weekly_cancel_data),true);

            if($result['weeklycount'] > 0){
                $result['weekly_details'] = $this->call_search_ytel($result['weekly_details']->toArray(),$lastweek,$todayDate_end);
                if(!array_key_exists('total_calls',$result['weekly_details'][0]))
                    $result['weekly_details'][0]['total_calls'] = 0;

                $result['weekly_sales_data'] = json_encode($this->fetch_recording_path($user_data,$weekly_sales_data),true);
            }
            else{
                $result['weekly_details'][0]['total_calls'] = 0;
                $result['weekly_sales_data'] = json_encode($weekly_sales_data,true);
            }

            

            $result['weekly_oppurtunites'] = json_encode($this->find_not_sale($user_data, $weekly_sales_data, $lastweek, $todayDate_end),true);

            $result['secondweeklycount'] = Saleslogs::whereBetween('purchdate',[$secondlastweek_start,$secondlastweek_end])->where('salesman',$sm_name)->count();
            $result['weeklydata'] = $this->FlagSighCheck($result['weeklycount'], $result['secondweeklycount']);
        
            $result['monthlycount'] = Saleslogs::whereBetween('purchdate',[$lastmonth,$todayDate_end])->where('salesman',$sm_name)->count();

            $result['monthly_cancel_sm_count'] = Cancellogs::whereBetween('CanDate',[$lastmonth, $todayDate_end])
                                               ->where('salesman',$sm_name)
                                               ->count();

            $result['monthly_wholesales_count'] = Saleslogs::whereBetween('purchdate',[$lastmonth, $todayDate_end])
                                                  ->whereIn('label1',['WHOLESALE','WSINBOUND','WSOUTBOUND'])
                                                  ->where('salesman',$sm_name)
                                                  ->count();

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

            $calen_res_arr =  $this->call_search_ytel($user_data, $lastmonth, $todayDate_end,$day_by_day=1);

            if(!empty($calen_res_arr))
            	$result['calendar_data'] = json_encode(array_merge($calen_res_arr[0],$result['monthly_sm_details']->toArray(),($this->sm_cancel_lead($lastmonth,$todayDate_end,$sm_name))->toArray()));
            else
            	$result['calendar_data'] = json_encode(array_merge($calen_res_arr,$result['monthly_sm_details']->toArray(),($this->sm_cancel_lead($lastmonth,$todayDate_end,$sm_name))->toArray()));


            $monthly_sales_data = Saleslogs::select('*',Saleslogs::raw("regexp_replace(phone, '[^0-9]', '') as phone_no"))
                                  ->whereBetween('purchdate', [$lastmonth, $todayDate_end])
                                  ->where('salesman',$sm_name)
                                  ->get()
                                  ->toArray();


            $monthly_cancel_data =  Cancellogs::select('*',
            					   Cancellogs::raw("regexp_replace(custPhone, '[^0-9]', '') as phone_no"))
                                   ->whereBetween('CanDate', [$lastmonth, $todayDate_end])
                                   ->where('salesman',$sm_name)
                                   ->get()
                                   ->toArray();

            $result['monthly_cancel_data'] = json_encode($this->fetch_recording_path($user_data,$monthly_cancel_data),true);

            if($result['monthlycount'] > 0){
                $result['monthly_details'] = $this->call_search_ytel($monthly_data->toArray(),$lastmonth,$todayDate_end);
                if(!array_key_exists('total_calls',$result['monthly_details'][0]))
                    $result['monthly_details'][0]['total_calls'] = 0;

                $result['monthly_sales_data'] = json_encode($this->fetch_recording_path($user_data,$monthly_sales_data),true);
            }
            else{
                // $result['monthly_details'] = $monthly_data->toArray();
                $result['monthly_details'][0]['total_calls'] = 0;
                $result['monthly_sales_data'] = json_encode($monthly_sales_data,true);
            }

            $result['monthly_oppurtunites'] = json_encode($this->find_not_sale($user_data, $monthly_sales_data, $lastmonth, $todayDate_end),true);

            // echo '<pre>';
            // print_r(json_decode($result['monthly_sales_data'],true));
            // die();


            $result['secondmonthlycount'] = Saleslogs::whereBetween('purchdate',[$secondlastmonth_start,$secondlastmonth_end])->where('salesman',$sm_name)->count();
            $result['monthlydata'] = $this->FlagSighCheck($result['monthlycount'], $result['secondmonthlycount']);


            
            
            if($request->isMethod('get'))
            {
            	$result['adv_range_flag'] = false;
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

                    $prev_sales_details = Saleslogs::select('salesman','purchdate',Saleslogs::raw('count(salesman) as sales_count '))
                                                    ->whereBetween('purchdate',[$first_date, $last_date])
                                                    ->where('salesman',$sm_name)
                                                    ->groupBy('salesman','purchdate')
                                                    ->get();

                    $calen_res_arr =  $this->call_search_ytel($user_data, $first_date, $last_date,$day_by_day=1);
            
		            if(!empty($calen_res_arr))
		            	$result['calendar_data'] = json_encode(array_merge($calen_res_arr[0],$prev_sales_details->toArray(),($this->sm_cancel_lead($first_date,$last_date,$sm_name))->toArray()));
		            else
		            	$result['calendar_data'] = json_encode(array_merge($calen_res_arr,$prev_sales_details->toArray(),($this->sm_cancel_lead($first_date,$last_date,$sm_name))->toArray()));
                    
                    return $result['calendar_data'];


                }

                elseif($request->post('leadDate') != ""){

                      // echo $request->post('leadDate');
                    $result['cal_date_data'] = array();

                    $caldate = $request->post('leadDate');
                    // $table_type = $request->post('tableType');
                    $cal_sales_data = Saleslogs::select('*',Saleslogs::raw("regexp_replace(phone, '[^0-9]', '') as phone_no"))
                                      ->where('salesman',$sm_name)
                                      ->whereBetween('purchdate', [$caldate, $caldate])
                                      ->get()
                                      ->toArray();
                    
                    $result['cal_date_data']['lead_info'] = $this->fetch_recording_path($user_data,$cal_sales_data);
                    
                    $result['cal_date_data']['opprt_info'] = $this->find_not_sale($user_data, $cal_sales_data, $caldate, $caldate);

                    $cal_cancel_data =  Cancellogs::select('*',
	            					  	Cancellogs::raw("regexp_replace(custPhone, '[^0-9]', '') as phone_no"))
	                                  	->whereBetween('CanDate', [$caldate, $caldate])
	                                  	->where('salesman',$sm_name)
	                                  	->get()
	                                  	->toArray();

            		$result['cal_date_data']['cancel_info'] = $this->fetch_recording_path($user_data,$cal_cancel_data);

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

                    $adv_lead_info_details = Saleslogs::select('*',Saleslogs::raw("regexp_replace(phone, '[^0-9]', '') as phone_no"))
                                  ->whereBetween('purchdate', [$start_range, $end_range])
                                  ->where('salesman',$sm_name)
                                  ->get()
                                  ->toArray();

                    $result['lead_info_details'] = json_encode($this->fetch_recording_path($user_data,$adv_lead_info_details),true);

                    $result['adv_range_oppurtunites'] = json_encode($this->find_not_sale($user_data, $adv_lead_info_details, $start_range, $end_range),true);

                    $adv_cancel_data =  Cancellogs::select('*',
	            					  	Cancellogs::raw("regexp_replace(custPhone, '[^0-9]', '') as phone_no"))
	                                  	->whereBetween('CanDate', [$start_range, $end_range])
	                                  	->where('salesman',$sm_name)
	                                  	->get()
	                                  	->toArray();

            		$result['adv_cancel_data'] = json_encode($this->fetch_recording_path($user_data,$adv_cancel_data),true);

                    return view('salesman-details',compact('result'));
                }

                
            }

            else{
                //do nothing
            }
        
    }

    public function find_not_sale($sm_data , $phone_data, $start_date, $end_date)
    {
        // echo '<pre>';
        // print_r($sm_data);
        $phone = array();
        $user_ids = array();
        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';

        foreach ($phone_data as $key => $ph_value) {
            array_push($phone, $ph_value['phone_no']);
        }

        foreach ($sm_data[0]['slaesagent'] as $key => $agentvalue) {
            array_push($user_ids, $agentvalue['user']);
        }


        $result_opprt = DB::connection('mysql3')->table('vicidial_closer_log')
                    ->select('vicidial_list.*','vicidial_closer_log.length_in_sec as call_time','recording_log.recording_id',
                            'recording_log.location','recording_log.filename')
                    ->join('vicidial_list',function($join1) use($phone) {
                        $join1->on('vicidial_list.lead_id','=','vicidial_closer_log.lead_id')
                        ->where('vicidial_list.security_phrase','=', 'Sales')
                        ->whereNotIn('vicidial_list.phone_number', $phone);
                        // ->where('vicidial_list.user','=','vicidial_closer_log.user')
                    })
                    ->join('recording_log',function($join2) use($user_ids){
                        $join2->on('vicidial_closer_log.closecallid','=','recording_log.vicidial_id')
                        ->whereIn('recording_log.user',$user_ids);
                    })
                    ->where('vicidial_closer_log.list_id','999')
                    ->where('vicidial_closer_log.length_in_sec','>','420')
                    ->where('vicidial_closer_log.campaign_id','=','Sales')
                    ->whereIn('vicidial_closer_log.user',$user_ids)
                    ->whereBetween('vicidial_closer_log.call_date',[$start_range,$end_range])
                    ->get()
                    ->toArray();

        

        return $result_opprt;

    }

    public function fetch_recording_path($usr_data, $sales_data)
    {

        $resArr = array();
        $user_ids = array();

        foreach ($usr_data[0]['slaesagent'] as $key => $agentvalue) {
            array_push($user_ids, $agentvalue['user']);
        }
        // echo '<pre>';
        // print_r($user_ids);
        foreach ($sales_data as $key => $sales_value) {

            $ph_no = $sales_value['phone_no'];

            $res_data = DB::connection('mysql3')->table('vicidial_closer_log')
                        ->select('vicidial_closer_log.phone_number as ytel_ph_no','recording_log.recording_id','recording_log.location','recording_log.filename')
                        ->join('recording_log',function($join1) use($user_ids,$ph_no){
                            $join1->on('vicidial_closer_log.closecallid','=','recording_log.vicidial_id')
                            ->where('vicidial_closer_log.phone_number','=',$ph_no)
                            ->whereIn('recording_log.user',$user_ids);
                        })
                        ->get()
                        ->toArray();

            if(!empty($res_data))
                $sales_value = array_merge($sales_value,json_decode(json_encode($res_data['0']),true));

            
            // print_r($sales_value);
            
            array_push($resArr, $sales_value);

        }

        return $resArr;

    }
      
}
