<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;
use App\Models\Salescalls;
use App\Models\Salesagent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesLogsExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{

    public $start_date, $end_date;
    public function __construct($start_date, $end_date, $tab_type)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->tab_type = $tab_type;
    }

	
    public function array(): array
    {

        $export_result = array();
        // echo '<pre>';
        if($this->tab_type == 'manager'){

            $manager_result = Saleslogs::select('t_o',Saleslogs::raw('SUM(downpay) as downpay_add'),
                              Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                              Saleslogs::raw('SUM(finterm) as finterm_add'), 
                              Saleslogs::raw('SUM(retail) as retail_add'),
                              Saleslogs::raw('count(t_o) as sales_count '))
                              ->with('team_lead_agent')
                              ->whereBetween('purchdate',[$this->start_date, $this->end_date])
                              ->where('t_o','<>','')
                              ->groupBy('t_o')
                              ->get()
                              ->toArray();

            $manager_result = array_values(array_filter($manager_result,function ($val){
                                            if (!empty($val['team_lead_agent']))
                                                return  $val;
                                            }));

            $manager_result_with_call = array();
            if(!empty($manager_result)){
                $manager_result_with_call = $this->count_transfer_call($manager_result); 
            }

            $export_result = $this->set_export_data($manager_result_with_call);
            // echo '<pre>';
            // print_r($manager_result_with_call);
            // die();
        }
        else{

            $sales_result =  Saleslogs::select('salesman',Saleslogs::raw('SUM(downpay) as downpay_add'),
                             Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                             Saleslogs::raw('SUM(finterm) as finterm_add'), 
                             Saleslogs::raw('SUM(retail) as retail_add'),
                             Saleslogs::raw('count(salesman) as sales_count '))
                             ->with('slaesagent')
                             ->leftJoin('vsctools_autoprotect.users as users','salesman','=','users.name')
                             ->whereBetween('purchdate',[$this->start_date, $this->end_date])
                             ->groupBy('salesman')
                             ->get()
                             ->toArray();

            $res_with_calls = array();
            if(!empty($sales_result))
            {
                $callData = json_decode($this->call_details_ytel(),true);
                $saleAgentArr = $this->crate_slagent_arr(array_column($sales_result,'slaesagent'));
                $diffArr = array_diff(array_column($callData,'user'), array_column($saleAgentArr,'user'));
                $restSaleAgentData = $this->find_agent_name($diffArr,$callData);
                $res_with_calls = array_merge($this->find_total_calls($sales_result,$this->start_date, $this->end_date),$restSaleAgentData);
                // echo '<pre>';
                // print_r($res_with_calls);
                // die();
            }

            $export_result = $this->set_export_data($res_with_calls);

        }
        
        // echo '<pre>';
        // print_r($export_result);
        // die();

        return $export_result;
    }


    public function find_total_calls($dataArr, $start_date, $end_date)
    {
    
        $resArr = array();
        $start_range = $start_date.' 00:00:00';
        $end_range = $end_date.' 23:59:59';
        // echo $start_range . "<br>";
        // echo $end_range . "<br>";
        // echo '<pre>';

        
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

        return $resArr;
    }

    public function headings(): array
    {
        return [
            'SALESMAN',
            'SALES',
            'DOWN PAYMENT',
            'FINANCE TERM',
            'DISCOUNT',
            'CALLS',
            'CLOSING %',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [

            1    => ['font' => ['bold' => true, 'size' => 12]],

        ];
    }


    public function crate_slagent_arr($dataArr)
    {
        $resArr = array();
        foreach ($dataArr as $key => $dataValue) {
            foreach ($dataValue as $key => $value) {
                array_push($resArr,$value);
            }
        }

        return $resArr;
        
    }

    public function set_export_data($dataArr)
    {
        $resArr = array();
        foreach ($dataArr as $key => $res_val) {
            if($this->tab_type == 'manager')
                $res_data['name'] = $res_val['manager'];
            else
                $res_data['name'] = $res_val['salesman'];

            if(!empty($res_val['sales_count'])){

                $res_data['sales'] = $res_val['sales_count'];
                $res_data['downpay'] = number_format(($res_val['downpay_add'] / $res_val['cuscost_add']) * 100,2);
                $res_data['finterm'] = number_format($res_val['finterm_add'] / $res_val['sales_count'] ,2);

                $discount = $res_val['retail_add'] - $res_val['cuscost_add'];

                if($discount < 0)
                    $discount = 0;

                $res_data['discount'] = number_format($discount / $res_val['sales_count']  ,2);
            }
            else{

                $res_data['sales'] = '0';
                $res_data['downpay'] = 'N/A';
                $res_data['finterm'] = 'N/A';
                $res_data['discount'] = 'N/A';

            }

            if(!empty($res_val['total_calls'])){
                $res_data['total_calls'] = $res_val['total_calls'];
                $res_data['closing'] = number_format(($res_val['sales_count'] / $res_val['total_calls']) * 100 ,2);
            }
            else{
                $res_data['total_calls'] = 'N/A';
                $res_data['closing'] = 'N/A';
            }

            array_push($resArr, $res_data);
        }

        return $resArr;
    }

    public function find_agent_name($agentArr,$callArr)
    {
        $resAgentArr = array();
        foreach ($agentArr as $value) {
            $data = Salesagent::select(Salesagent::raw('user_ytel_name as salesman'),'users.avatar')
                    ->leftJoin('vsctools_autoprotect.users as users','user_ytel_name','=','users.name')
                    ->where('user','=',$value)
                    ->get()
                    ->toArray();
            $index = array_search($value, array_column($callArr,'user'));
            $data[0]['sales_count'] = 0;
            $data[0]['total_calls'] = $callArr[$index]['total_calls'];
            array_push($resAgentArr,$data[0]);

        }

        return $resAgentArr;
    }

    public function call_details_ytel()
    {
        
        $start_range = $this->start_date.' 00:00:00';
        $end_range = $this->end_date.' 23:59:59';

        $sub_res = Salescalls::select('user','phone_number')
                  ->where('list_id','999')
                  ->where('length_in_sec','>','20')
                  ->where('campaign_id','=','Sales')
                  ->whereBetween('call_date',[$start_range,$end_range])
                  // ->groupBy('user')
                  ->distinct('phone_number');
                  // ->get()->toArray();

        $result = DB::connection('mysql2')
                  ->table( DB::raw("({$sub_res->toSql()}) as sub_res"))
                  ->select('user',DB::raw('count(user) as total_calls'))
                  ->mergeBindings($sub_res->getQuery())
                  ->groupBy('user')
                  ->get()->toArray();

        return json_encode($result,true);

    }

    public function count_transfer_call($managerData)
    {

        $start_range = $this->start_date.' 00:00:00';
        $end_range = $this->end_date.' 23:59:59';

        foreach ($managerData as $key => $mValue) {
            $managerName = $mValue['team_lead_agent'][0]['user_ytel_name'];
            $managerData[$key]['manager'] = $managerName;
            $managerData[$key]['total_calls'] = 0;
            foreach ($mValue['team_lead_agent'] as $agnetKey => $agentValue) {
                
                $sub_res =  Salescalls::select('user','phone_number')
                            ->where('user','=',$agentValue['user'])
                            ->where('list_id','999')
                            ->where('length_in_sec','>','20')
                            ->where('campaign_id','=','To')
                            ->whereBetween('call_date',[$start_range,$end_range])
                            ->distinct('phone_number');
                  

                $result = DB::connection('mysql2')
                          ->table( DB::raw("({$sub_res->toSql()}) as sub_res") )
                          ->select('user',DB::raw('count(user) as total_calls'))
                          ->mergeBindings($sub_res->getQuery())
                          ->groupBy('user')
                          ->get()
                          ->toArray();

                if(!empty($result)){
                    $managerData[$key]['total_calls'] += $result[0]->total_calls;
                }
            }

        }

        return $managerData;
    }
        
}


