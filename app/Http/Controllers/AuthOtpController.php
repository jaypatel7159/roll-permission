<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserOtp;
// use Client;
use Twilio\Rest\Client;

class AuthOtpController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    // public function register()
    // {
    //     return view('auth.otpRegisterVerification');
    // }

    public function login()
    {
        return view('auth.otpLogin');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    // public function generateRegister()
    // {
    //     $user = Auth::user()->mobile_no;
    //     dd($user);
    //     /* Generate An OTP */
    //     $userOtp = $this->generateOtp($user);

    //     $userOtp->sendSMS($user);
    //     //dd($userOtp->otp);
    //     $sid = getenv("TWILIO_SID");
    //     $token = getenv("TWILIO_TOKEN");
    //     $sender = getenv("TWILIO_FROM");
    //     $twilio = new Client($sid, $token);

    //     $message = $twilio->messages
    //         ->create(
    //             "+91 $user", // to
    //             [
    //                 "body" => "Otp: $userOtp",
    //                 "from" => $sender
    //             ]
    //         );

    //     return redirect()->route('otp.verificationRegister', ['user_id' => $userOtp->user_id])
    //         ->with('success',  "OTP has been sent on Your Mobile Number.");
    // }
    public function generate(Request $request)
    {
        /* Validate Data */
        $request->validate([
            'mobile_no' => 'required|exists:users,mobile_no'
        ]);

        /* Generate An OTP */
        $userOtp = $this->generateOtp($request->mobile_no);

        $userOtp->sendSMS($request->mobile_no);
        //dd($userOtp->otp);
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $sender = getenv("TWILIO_FROM");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+91 $request->mobile_no", // to
                [
                    "body" => "Otp: $userOtp",
                    "from" => $sender
                ]
            );

        return redirect()->route('otp.verification', ['user_id' => $userOtp->user_id])
            ->with('success',  "OTP has been sent on Your Mobile Number.");
    }


    public function generateOtp($mobile_no)
    {
        // dd($mobile_no);
        $user = User::where('mobile_no', $mobile_no)->first();

        /* User Does not Have Any Existing OTP */
        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
        // dd($userOtp);
        $now = now();

        if ($userOtp && $now->isBefore($userOtp->expire_at)) {
            return $userOtp;
        }
        // if ($user) {
        /* Create a New OTP */
        return  UserOtp::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => $now->addMinutes(10)
        ]);
    }



    /**
     * Write code on Method
     *
     * @return response()
     */
    // public function verificationRegister($user_id)
    // {
    //     return view('auth.otpRegisterVerification')->with([
    //         'user_id' => $user_id
    //     ]);
    // }
    public function verification($user_id)
    {
        return view('auth.otpVerification')->with([
            'user_id' => $user_id
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function loginWithOtp(Request $request)
    {
        /* Validation */
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ]);

        /* Validation Logic */
        $userOtp   = UserOtp::where('user_id', $request->user_id)->where('otp', $request->otp)->first();

        $now = now();
        if (!$userOtp) {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        } else if ($userOtp && $now->isAfter($userOtp->expire_at)) {
            return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
        }

        $user = User::whereId($request->user_id)->first();

        if ($user) {

            $userOtp->update([
                'expire_at' => now()
            ]);

            Auth::login($user);

            return redirect('/home');
        }

        return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
    }
}