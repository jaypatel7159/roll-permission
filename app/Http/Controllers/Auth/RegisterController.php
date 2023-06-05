<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Auth\StatefulGuard;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = RouteServiceProvider::REGISTER;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required', new ReCaptcha],
            'mobile_no' => ['required', 'numeric', 'digits:10', 'unique:users']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'mobile_no' => $data['mobile_no'],
        ]);

        $num = $data['mobile_no'];

        $userOtp = $this->generateOtp($num);

        $userOtp->sendSMS($num);
        //dd($userOtp->otp);
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $sender = getenv("TWILIO_FROM");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+91 $num", // to
                [
                    "body" => "Otp: $userOtp",
                    "from" => $sender
                ]
            );

        // auth()->login($user);
        //Auth::guard('admin')->login($user);


        return redirect()->route('otp.verificationRegister', ['user_id' => $userOtp->user_id])->with('success',  "OTP has been sent on Your Mobile Number.");

        //  return redirect()->route('otp.register');
    }

    public function generateOtp($mobile_no)
    {
        //dd($mobile_no);
        $user = User::where('mobile_no', $mobile_no)->first();

        /* User Does not Have Any Existing OTP */
        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
        // dd($userOtp);
        $now = now();

        if ($userOtp && $now->isBefore($userOtp->expire_at)) {
            return $userOtp;
        }

        /* Create a New OTP */
        return  UserOtp::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => $now->addMinutes(10)
        ]);
    }



    public function verificationRegister($user_id)
    {
        // auth()->login($user_id);
        return view('auth.otpRegisterVerification')->with([
            'user_id' => $user_id
        ]);
    }

    // public function registerWithOtp(Request $request)
    // {
    //     /* Validation */
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'otp' => 'required'
    //     ]);

    //     /* Validation Logic */
    //     $userOtp   = UserOtp::where('user_id', $request->user_id)->where('otp', $request->otp)->first();

    //     $now = now();
    //     if (!$userOtp) {
    //         return redirect()->back()->with('error', 'Your OTP is not correct');
    //     } else if ($userOtp && $now->isAfter($userOtp->expire_at)) {
    //         return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
    //     }

    //     $user = User::whereId($request->user_id)->first();

    //     if ($user) {

    //         $userOtp->update([
    //             'expire_at' => now()
    //         ]);

    //         Auth::login($user);
    //         return redirect('/home');
    //     }

    //     return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
    // }
}