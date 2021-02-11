<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile-page');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   $user = User::findOrFail($id);
        //dd($user); 
        return view('profile.show', [ 'user' => $user] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        return view('profile.edit');
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {  
        request()->validate([
         'name' => ['string', 'required', 'max:40'],
         'email'=> [ 'string', 'required', 'email','max:255'],
         'avatar' => ['file'],
         'password' => ['string', 'min:8', 'max:15', 'alpha_dash']

        ]);

        $user = User::find($id);
        $user->name = request('name');
        $user->email = request('email');

        if (request()->hasFile('avatar')) {
            $avatar_path = request('avatar')->store('avatars');
            $user->avatar = $avatar_path;
        }

        //$user->password = Hash::make(request('password'));
        $user->save();
        return redirect('/profile-page');
      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
