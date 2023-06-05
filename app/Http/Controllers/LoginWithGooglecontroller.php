<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginWithGooglecontroller extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        // dd($user);
        $newUser = User::where('email', $user->email)->first();
        if ($newUser) {

            Auth::login($newUser);

            return redirect()->intended('home');
        } else {
            // if (is_null($newUser)) {
            $name['name'] = $user->name;
            $email['email'] = $user->email;
            // $password[''] => Hash::make('123456dummy'),
            $google_id['google_id'] = $user->id;
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => Hash::make('123456dummy'),
                'google_id' => $user->id,
                'image' => $user->avatar,
            ]);
        }
        Auth::login($newUser);
        return redirect('home');
        // try {

        //     $user = Socialite::driver('google')->user();

        //     $finduser = User::where('google_id', $user->id)->first();

        //     if ($finduser) {

        //         Auth::login($finduser);

        //         return redirect()->intended('dashboard');
        //     } else {
        //         $newUser = User::create([
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'password' => Hash::make('123456dummy'),
        //             'google_id' => $user->id,
        //         ]);

        //         Auth::login($newUser);

        //         return redirect()->intended('dashboard');
        //     }
        // }
        // try {
        //     //if Authentication is successfull then return the user details.
        //     $user = Socialite::driver('facebook')->user();


        //     $provider_id = $user->getId();
        //     $name = $user->getName();
        //     $email = $user->getEmail();

        //     $user = User::firstOrCreate([
        //         'google_id' => $provider_id,
        //         'name'        => $name,
        //         'email'       => $email,
        //         // 'avatar'      => $avatar,
        //     ]);


        //     Auth::login($user, true);

        //     return redirect()->route('home');
        // } catch (Exception $e) {
        //     return redirect()->route('home');
        //     //  dd($e->getMessage());

        // }
    }
}
