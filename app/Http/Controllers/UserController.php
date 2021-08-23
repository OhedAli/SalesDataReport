<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Image;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::all();
        /*echo '<pre>';
        print_r($users);*/

        return view('user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $filename = 'profile.png';
        if($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $filename = strtolower(substr($request->name,0,strpos($request->name, ' '))) . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/images/uploads/avatars/' . $filename ) );
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'type' => 'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'avatar' => $filename
        ]);
        event(new Registered($user));
        return redirect()->route('user.index')->with('success', 'User created successfully!');

    }

      public function userReportview()
    {
            $users = User::all();
            return view('user.userReport',compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::find($id);
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $reports = $request->data_report;
        
        $daily_report ='0';
        $weekly_report ='0';
        $monthly_report ='0';
        if(!empty($reports))
        {
              if(in_array('weekly', $reports) )
        {
          $weekly_report ='1';
                }
            if(in_array('monthly', $reports))
            {
                $monthly_report ='1';
                    }
            if(in_array('daily', $reports))
              {
                $daily_report ='1';
                    }
        }
      
       // echo 'Daily Report--'.$daily_report."monthly-->".$monthly_report."weekly-->".$weekly_report;

        

        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255'
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->type = $request->type;
        $user->email = $request->email;
        $user->daily_report = $daily_report;
        $user->weekly_report = $weekly_report;
        $user->monthly_report = $monthly_report;


        if($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $filename = strtolower(substr($request->name,0,strpos($request->name, ' '))) . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/images/uploads/avatars/' . $filename ) );
            $user->avatar = $filename;
        }

        if(!empty($request->password)){
        $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('user.index')->with('success', 'User updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully!');
    }


}
