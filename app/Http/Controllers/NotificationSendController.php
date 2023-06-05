<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_key =  $request->token;

        Auth::user()->save();

        return response()->json(['Token successfully stored.']);
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        $serverKey = 'AAAAwXpv70s:APA91bFmuGf05lM39KQKgfuKe9-exs0AfL8lJC8FGk78oysqrxB78dkwF571Iig8MedioC5RuKUk8sLIhYlhjqp6LbkSHX62stwZvwwFV28tV0K_NjgNsYQJB3n6aE9sSzC-VChms2DB'; // ADD SERVER KEY HERE PROVIDED BY FCM

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        //dd($result);
        return redirect()->back();
    }
}
