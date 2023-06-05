<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function facebookCallback()
    {
        // $user = Socialite::driver('facebook')->stateless()->user();

        $user = Socialite::driver('facebook')->user();
        // dd($user);
        $newUser = User::where('email', $user->email)->first();
        if ($newUser) {

            Auth::login($newUser);

            return redirect()->intended('home');
        } else {
            // if (is_null($newUser)) {
            $name['name'] = $user->name;
            $email['email'] = $user->email;
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => Hash::make('123456dummy'),
                'facebook_id' => $user->id,
                'image' => $user->avatar,
            ]);
        }
        Auth::login($newUser);
        return redirect('home');
    }
}
