<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< HEAD
        //
=======
        $users = User::all();
        /*echo '<pre>';
        print_r($users);*/

        return view('user.index',compact('users'));
>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
<<<<<<< HEAD
        //
=======
        return view('user.create');
>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
<<<<<<< HEAD
        //
=======
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
        ]);
        event(new Registered($user));
        return redirect()->route('user.index')->with('success', 'User created successfully!');
>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
<<<<<<< HEAD
        //
=======

>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
<<<<<<< HEAD
        //
=======
        $user = User::find($id);
        return view('user.edit', ['user' => $user]);
>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
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
<<<<<<< HEAD
        //
=======
        //dd($request->all());
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255'
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->type = $request->type;
        $user->email = $request->email;
        if(!empty($request->password)){
        $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
<<<<<<< HEAD
        //
=======
    
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully!');
>>>>>>> a21f69f5a40535aa19326af8202a6eb2d9acfcf5
    }
}
