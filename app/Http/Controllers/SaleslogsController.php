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

    public  function show(Request $request){
        $customer = Saleslogs::find($request->id);
        
        return view('SalesShow',compact('customer'));
    }

    

}
