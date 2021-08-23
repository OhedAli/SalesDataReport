<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use Illuminate\Support\Facades\DB;
    use App\Models\Saleslogs;
    use App\Models\Salescalls;
    use App\Models\Cancellogs;
    use App\Models\Salesagent;
    use App\Models\Ytel;
    use App\Models\User;
    use Mail;
class EmailingController extends Controller
{
    public function emailsend(){
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

        $result['todaycount'] = Saleslogs::whereBetween('purchdate',[$todayDate_start, $todayDate_end])->count();
        $result['today_details'] = $this->get_saleslog_details_sm($todayDate_start, $todayDate_end);
         if($result['todaycount'] > 0){
            $res_today = $this->call_search_ytel($result['today_details']->toArray(),$todayDate_start,$todayDate_end);
           
            $result['today_details'] = json_encode($res_today,true);
           

        }

        $result['weeklycount'] = Saleslogs::whereBetween('purchdate',[$lastweek,$todayDate_end])->count();
        $result['weekly_details'] = $this->get_saleslog_details_sm($lastweek, $todayDate_end);
         if($result['weeklycount'] > 0){
                $result['weekly_details'] = $this->call_search_ytel($result['weekly_details']->toArray(),$lastweek,$todayDate_end);
                if(!array_key_exists('total_calls',$result['weekly_details'][0]))
                    $result['weekly_details'][0]['total_calls'] = 0;
            }
            else{
                $result['weekly_details'][0]['total_calls'] = 0;
            }

        $result['monthly_details'] = $this->get_saleslog_details_sm($lastmonth, $todayDate_end);

        $res = $this->MailSender($result);
        echo $res;
        //print_r($result['weekly_details']);

    }
     public function get_saleslog_details_sm($startDate, $endDate)
    {
        $resData =  Saleslogs::select('salesman', 'users.avatar',
                    Saleslogs::raw('SUM(downpay) as downpay_add'),
                    Saleslogs::raw('SUM(cuscost) as cuscost_add'),
                    Saleslogs::raw('SUM(finterm) as finterm_add'), 
                    Saleslogs::raw('SUM(retail) as retail_add'),
                    Saleslogs::raw('count(salesman) as sales_count '))
                    ->with('slaesagent')
                    ->leftJoin('vsctools_autoprotect.users as users','salesman','=','users.name')
                    ->whereBetween('purchdate',[$startDate, $endDate])
                    ->groupBy('salesman')
                    ->get();

        return $resData;
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
     public function call_search_ytel($dataArr, $start_date, $end_date)
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
            //die();
            return $resArr;
        
        
        
    }
    public function MailSender($result){
        $weekly_data = $result['weekly_details'];
        $dynamic_data = '';
        foreach ($weekly_data as $key => $value) {
            $salesman = $value['salesman'];
            $sales_count = $value['sales_count'];
            $total_calls = $value['total_calls'];
            $downpay_add = $value['downpay_add'];
            $cuscost_add = $value['cuscost_add'];
            $finterm_add = $value['finterm_add'];
            $retail_add = $value['retail_add'];
            $downpayment = round(($downpay_add/$cuscost_add)* 100);
            $finterm = round($finterm_add/$sales_count);
            $discount = round($retail_add - $cuscost_add);
            $discount = '$'.round(($discount / $sales_count));
           /* if ($total_calls !== '0') {
            	 $cov_rate = round(($sales_count/$total_calls)*100).'%';
            }else{
            	$cov_rate = 'Not Avialable';
            }*/
            
            $dynamic_data .= '<tr style="    background-color: #f9f9f9;">
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$salesman.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;background-color: #f1f1f1;border-bottom:1px solid #dee2e6;" valign="top" >'.$total_calls.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$cuscost_add.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$retail_add.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$downpayment.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$finterm.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:green !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$discount.'
                                            </td>
                                          </tr>';
           
          
            
        }
        $massage = $this->massageTemplate($dynamic_data);
        $ret = $this->emailTemplate($massage);
        return $ret;
    }

    public function emailTemplate($massage){

      $data = array('name'=>"Auto Protect USA");
     
      try{
        $mails = Mail::send('email-template', $data, function($message) {
           $message->to('ohed.ali@codeclouds.in', 'Ohed Ali')->subject
              ('Auto Protect Dashboard Report');
           $message->from('support@luxurywarehousedepot.com','Auto Protect USA');

        });
         
        echo "Basic Email Sent. Check your inboxs.";
      }catch (Exception $ex) {
         echo $ex->getMessage();
        //return "We've got errors!";
      }

		}

		public function massageTemplate($dynamic_data){
		   	$message = '<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
    </title>
  </head>
  <body>
    <center>
      <table align="center" bgcolor="#eeeeee" border="0" cellpadding="0" cellspacing="0" style="margin: 0px auto;line-height: 0;" width="100%">
        <tbody>
          <tr style="line-height:0;">
            <td align="center" valign="top">
              <div style="max-width:1000px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                  <tbody>
                    <tr style="line-height:0;">
                      <td align="left" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td align="left" valign="top">&nbsp;
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table border="0" cellpadding="0" cellspacing="0" style=" background:#eee;" width="100%" >
                          <tbody>
                            <tr>
                              <td style="padding:0;vertical-align:top;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  <tbody>
                                    <tr>
                                      <td align="left" colspan="2" height="10" valign="top">&nbsp;
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top" width="6">&nbsp;
                                      </td>
                                      <td align="left" style="" valign="top" width="6">&nbsp;
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" style="background:#fff; padding: 15px;" width="100%">
                          <tbody>
                            <tr>
                              <td style="padding:0;vertical-align:top;border:1px solid #eeeef5; border-bottom: 0px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  
                                    <thead>
                                      <tr>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">SalesMan
                                        </th>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">Sales
                                        </th>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">DOWN PAYMENT
                                        </th>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">FINANCE TERM
                                        </th>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">DISCOUNT
                                        </th>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">Calls
                                        </th>
                                        <th valign="top" width="14.2%" align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:#000; font-weight:bold; text-transform: uppercase;background-color: #dee2e6;    padding: 0.75rem;" valign="top">Closing %
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                          '.$dynamic_data.'
                                          </tbody>
                                    <tr>
                                      <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                      </table>
                                    </tr>
                                  
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table border="0" cellpadding="0" cellspacing="0" style=" background:#eee;" width="100%" >
                          <tbody>
                            <tr>
                              <td style="padding:0;vertical-align:top;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  <tbody>
                                    <tr>
                                      <td align="left" colspan="2" height="10" valign="top">&nbsp;
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left" valign="top" width="6">&nbsp;
                                      </td>
                                      <td align="left" style="" valign="top" width="6">&nbsp;
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
                
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </center>
    <br /> 
  </body>
</html>
		';
		return $message;
		   }

		   public function testemailsend()
    {
       
	$data = array('name'=>"Virat Gandhi");
  	try{
      $mails = Mail::send('email-template', $data, function($message) {
         $message->to('ohed.ali@codeclouds.in', 'Test from laravel')->subject
            ('Laravel Basic Testing Mail');
         $message->from('support@luxurywarehousedepot.com','Virat Gandhi');

      });
       print_r($mails);
      echo "Basic Email Sent. Check your inboxs.";
	  }catch (Exception $ex) {
	     echo $ex->getMessage();
	    //return "We've got errors!";
	}

     
    }
}