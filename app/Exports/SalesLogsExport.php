<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Saleslogs;
use App\Models\Salescalls;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesLogsExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{

    public $start_date, $end_date;
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

	
    public function array(): array
    {

        $export_result = array();
        // echo '<pre>';
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

        if(!empty($sales_result))
        {
            $res_with_calls = $this->find_total_calls($sales_result,$this->start_date, $this->end_date);
        }

        foreach ($res_with_calls as $key => $res_val) {

            $res_data['salesman'] = $res_val['salesman'];
            $res_data['sales'] = $res_val['sales_count'];
            $res_data['downpay'] = number_format(($res_val['downpay_add'] / $res_val['cuscost_add']) * 100,2);
            $res_data['finterm'] = number_format($res_val['finterm_add'] / $res_val['sales_count'] ,2);

            $discount = $res_val['retail_add'] - $res_val['cuscost_add'];

            if($discount < 0)
            	$discount = 0;

            $res_data['discount'] = number_format($discount / $res_val['sales_count']  ,2);

            if(!empty($res_val['total_calls'])){
                $res_data['total_calls'] = $res_val['total_calls'];
                $res_data['closing'] = number_format(($res_val['sales_count'] / $res_val['total_calls']) * 100 ,2);
            }
            else{
                $res_data['total_calls'] = 'N/A';
                $res_data['closing'] = 'N/A';
            }

            array_push($export_result, $res_data);
        }

        
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
        
}


