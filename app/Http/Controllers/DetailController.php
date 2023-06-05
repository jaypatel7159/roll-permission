<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // public function createDetail(Request $request)
    // {
    //     Detail::create($request->all());

    //     return response()->json(['msg' => 'created']);
    // }
    public function  createDetail(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            // 'roles' => 'required',
            'hobbies.*' => 'required',
            'image' => 'required',

        ]);
        // dd(implode(",", $request->hobbies));
        // $input = $request->all();
        // // // //dd($input);
        // $input['password'] = Hash::make($input['password']);

        // foreach ($request->hobbies as $key => $value) {
        //     User::create($value);
        // }


        User::create([
            $img = $request->file('image')->getClientOriginalName(),
            Storage::disk('public')->putFileAs('images', new File($request->file('image')), $img),


            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request['password']),
            "hobbie" => implode(", ", $request->hobbie),
            "image" => $img,

        ]);
        //dd($user);
        // $user = User::create($input);

        // $user->assignRole($request->input('roles'));

        return response()->json(['msg' => 'user create']);
        // return redirect()->route('users.index')
        //     ->with('success', 'User created successfully');
    }
}
