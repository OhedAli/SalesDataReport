<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SalesLogsExport;
use Maatwebsite\Excel\Facades\Excel;  

class SalesExportController extends Controller
{
    public function index(Request $request, $date_range, $file_type, $tab_type)
    {

    	date_default_timezone_set("America/Chicago");

    	if(strpos($date_range,'-') !== false)
    	{
    		$dateArr = explode('-', $date_range);
	    	$start_range = date('Y-m-d', strtotime($dateArr[0]));
	    	$end_range = date('Y-m-d', strtotime($dateArr[1]));
    	}

    	else
    	{
    		if($date_range == 'daily' || $date_range == 'today')
    		{
    			$start_range = $end_range = date('Y-m-d');
    		}
    		elseif($date_range == 'yesterday')
    		{
    			$start_range = date('Y-m-d',strtotime('-1 day'));
    			$end_range = date('Y-m-d');
    		}
    		elseif($date_range == 'weekly')
    		{
    			if(date('D') != 'Mon')
    				$start_range = date('Y-m-d', strtotime('last Monday'));
    			else
    				$start_range = date('Y-m-d');

    			$end_range = date('Y-m-d');
    		}
    		elseif($date_range == 'last_week')
    		{
    			if(date('D') != 'Mon')
    			{
    				$this_week_start = date('Y-m-d', strtotime('last Monday'));
    				$end_range = date('Y-m-d', strtotime('-1 days', strtotime($this_week_start)));
    				$start_range = date('Y-m-d', strtotime('-7 days', strtotime($end_range)));
    			}
    			else
    			{
    				$this_week_start = date('Y-m-d');
    				$start_range = date('Y-m-d', strtotime('last Monday'));
    				$end_range = date('Y-m-d', strtotime('-1 days', strtotime($this_week_start)));
    			}

    		}
    		elseif($date_range == 'monthly')
    		{
    			$start_range = date('Y-m-01');
    			$end_range = date('Y-m-d');
    		}

    		else
    		{
    			$days_no_prev_mnth = date('t',mktime(0,0,0, date('n') -1 ));
    			$start_range = date('Y-m-01', strtotime('-1 month'));
    			$end_range = date('Y-m-'.$days_no_prev_mnth, strtotime('-1 month'));
    		}


    	}

    	// echo $start_range."<br>".$end_range."<br>".$file_type;
    	// die();

    	$export_file_name = 'salesagentlogs-'.date('mdy').'.'.$file_type;
    	return Excel::download(new SalesLogsExport($start_range,$end_range,$tab_type),$export_file_name);
    }
}
