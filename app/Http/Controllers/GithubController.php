<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }


    public function handleGithubCallback()
    {
        // $user = Socialite::driver('facebook')->stateless()->user();

        $user = Socialite::driver('github')->user();
        // dd($user);
        $newUser = User::where('email', $user->email)->first();
        if ($newUser) {

            Auth::login($newUser);

            return redirect()->intended('home');
        } else {
            // if (is_null($newUser)) {
            $name['name'] = $user->nickname;
            $email['email'] = $user->email;
            $newUser = User::create([
                'name' => $user->nickname,
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
