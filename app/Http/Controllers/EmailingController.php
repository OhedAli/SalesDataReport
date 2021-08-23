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
    use App\Mail\SendEmailUser;
    use App\jobs\SendEmailJob;
    use Mail;
    use App\Exports\SalesLogsExport;

class EmailingController extends DashboardController
{
    static $timeRange = '';
    public $time = '';
    public function emailsend($timeRange){

        Self::$timeRange= $timeRange;

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

        if($timeRange == 'monthly')
        {   
            $startDate = $secondlastmonth_start;
            $endDate = $secondlastmonth_end;
            $this->time = date('F',strtotime('-1 month'));
        }
        elseif($timeRange == "weekly")
        {   
            $startDate = $lastweek;
            $endDate = $todayDate_end;
            $this->time = date('jS F,y',strtotime($lastweek)).' - '.date('jS F,y',strtotime($todayDate_end));
        }
        else
        {      
            $startDate = $yesterdayDate_start;
            $endDate =  $yesterdayDate_start;
            $this->time = date('jS F,y ',strtotime($yesterdayDate_start));
        }

        $resultCount= Saleslogs::whereBetween('purchdate',[$startDate,$endDate])->count();

        $resultDetails = $this->get_saleslog_details_sm($startDate, $endDate);
        
        $callData = json_decode($this->call_details_ytel($startDate,$endDate),true);
        $saleAgentArr = $this->crate_slagent_arr(array_column($resultDetails->toArray(),'slaesagent'));
        $diffArr = array_diff(array_column($callData,'user'), array_column($saleAgentArr,'user'));
        $restSaleAgentData = $this->find_agent_name($diffArr,$callData);
        
        // echo '<pre>';
        // print_r($restSaleAgentData);
        // print_r(json_decode($this->call_details_ytel($lastmonth,$todayDate_end),true));
        // die();

        if($resultCount > 0){

            $result = $this->call_search_ytel($resultDetails->toArray(),$startDate,$endDate);
            $result = array_merge($result,$restSaleAgentData);
            //$result['monthly_details'] = json_encode($res_monthly,true);
            //$result['monthly_pifs_details'] = json_encode($this->get_saleslog_details_sm_pifs($lastmonth,$todayDate_end, $res_monthly),true);
            $this->MailSender($result);
        }
       

   

    }

    
    public function MailSender($result){

        $salesReport = array(
          0=>['Sales Man','Sales','DOWN PAYMENT','FINANCE TERM','DISCOUNT','Calls','Closing %']
        );
        $counter= 1;
        // echo "<pre>";
        // print_r($result);
        // exit;
        $dynamic_data = '';

        foreach ($result as $key => $value) {
            $salesman =  $value['salesman'];
            $sales_count = $value['sales_count'];
            $total_calls = $value['total_calls'];
            if($value['sales_count'] == 0)
            {
                $downpayment = 'N/A';
                $finterm = 'N/A';
                $discount = 'N/A';
                
             }
             else
             {
                $downpay_add = (!empty($value['downpay_add'])?$value['downpay_add']:0);
                $cuscost_add = $value['cuscost_add'];
                $finterm_add = $value['finterm_add'];
                $retail_add = $value['retail_add'];
                $downpayment = round(($downpay_add/$cuscost_add)* 100 , 2).'%';
                $finterm = round(((int)$finterm_add/(int)$sales_count) , 2);
                $discount = round((int)$retail_add - (int)$cuscost_add);
                $discount = '$'.($discount < 0 ? 0 : round($discount / $sales_count));
             }
 
            // $calls = ($total_calls !=''? $total_calls: 'Not Availabe');
            $conv_rate =((int)$total_calls !='' ? (number_format((float)((int)$sales_count/(int)$total_calls) * 100,2)) . '%' : 'N/A');
           /* if ($total_calls !== '0') {
               $cov_rate = round(($sales_count/$total_calls)*100).'%';
            }else{
              $cov_rate = 'Not Avialable';
            }*/

            if($conv_rate < 7 ){
                $smColor = $color = 'red';
            }
            elseif($conv_rate > 8 ){
                $smColor = $color = 'green';
            }
            else{
                $smColor = 'blue';
                $color = 'grey';
            }


            $salesReport[$counter]['salesman'] = $value['salesman'];
            $salesReport[$counter]['sales_count'] = $value['sales_count'];
            $salesReport[$counter]['down_payment'] = $downpayment;
            $salesReport[$counter]['finterm'] = $finterm;
            $salesReport[$counter]['discount'] = $discount;
            $salesReport[$counter]['calls'] = $total_calls;
            $salesReport[$counter]['closing'] = $conv_rate;
           
            $counter++;
            
            $dynamic_data .= '<tr style="    background-color: #f9f9f9;">
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$smColor.' !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$salesman.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$color.' !important;width: 14.2%;padding: 0.75rem;background-color: #f1f1f1;border-bottom:1px solid #dee2e6;" valign="top" >'.$sales_count.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$color.' !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$downpayment.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$color.' !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$finterm.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$color.' !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$discount.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$color.' !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$total_calls.'
                                            </td>
                                            <td align="left" style=" font: 12px/24px Arial,Helvatica,sans-serif;color:'.$color.' !important;width: 14.2%;padding: 0.75rem;border-bottom:1px solid #dee2e6;" valign="top" >'.$conv_rate.'
                                            </td>
                                          </tr>';
           
          
            
        }

        
        $data = array('name'=> $dynamic_data);
        // print_r($data); die();

        $ret = $this->testmailsend($data,$salesReport);
        return $ret;
        
    }

	 public function testmailsend($data, $salesReport)
    {
       
        $path = public_path(); 
        if(Self::$timeRange== "monthly")
            $month = date('F',strtotime('-1 month'));
        else
            $month = date('F'); 


        if(!is_dir($path."/file-report/".$month))
        {
            mkdir($path."/file-report/".$month);
        }

        if(Self::$timeRange== "monthly")
        {
            $sentemails = User::select('email')->where('monthly_report','1')->get()->toArray();
            $fileName = $path."/file-report/".$month."/Sales-Report-Monthly-".date('ydm').".csv";
            
        }
        elseif(Self::$timeRange== "weekly")
        {   
            $sentemails = User::select('email')->where('weekly_report','1')->get()->toArray();
            $fileName = $path."/file-report/".$month."/Sales-Report-Weekly-".date('ydm').".csv";  
        }
        else
        {
            $sentemails = User::select('email')->where('daily_report','1')->get()->toArray();
            $fileName = $path."/file-report/".$month."/Sales-Report-Daily-".date('ydm').".csv";
        }

        $file = fopen($fileName,"w");

        foreach ($salesReport as $line) {
              fputcsv($file, $line);
            }


        $emails = array_column($sentemails, 'email');
      	try{
            
          $mails = Mail::send('email-template', $data, function($message) use ($emails, $fileName) {
            foreach($emails as $key => $value)
            {
               $message->to($value)->subject
                ('Auto Protect Dashboard '.ucfirst(Self::$timeRange).'('. $this->time .') Report');
             $message->from('support@vsctools.dev','Auto Protect USA');
             $message->attach($fileName);
            }
        

          });
          //die();
           
          echo "Basic Email Sent. Check your inboxs.";
    	  }catch (Exception $ex) {
    	     echo $ex->getMessage();
    	    //return "We've got errors!";
    	}
    }
      public function userReportview()
    {
      $email = '';
            $users = User::all();
            foreach ($users as $key => $user) {
              $email .= $user->email.':'.$user->data_report.'|';

            }
            return $email;
    }

}