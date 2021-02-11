<?php

namespace App\Http\Controllers;
use App\Models\Saleslogs;
use Illuminate\Http\Request;

class SaleslogsController extends Controller
{
    public function index(){

        $total_customers = Saleslogs::all();
        // print_r($total_customers);
        return view('sales',compact('total_customers'));

      
    }

    public function showdetails($id){
        $customer_details = Saleslogs::where('id','=',$id)->get()->toArray();
        //echo '<pre>';
        //print_r($custome_details);
        return view('sales-view',compact('customer_details'));
    }    

}
