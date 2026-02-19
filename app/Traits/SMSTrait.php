<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SMSTrait
{
    function sendSMS($phone, $message, $is_otp = false)
    {
        if ($is_otp) {
            if (env('OTP_SMS_SEND_BY_NETTYFISH')) {
                Log::info("NETTYFISH_OTP");
                $response =  Http::get('https://sms.nettyfish.com/api/v2/SendSMS?ApiKey=' . env('NETTYFISH_API_KEY') . '&ClientId=' . env('NETTYFISH_CLIENTID') . '&SenderId=' . env('SMS_SENDER_ID') . '&MobileNumbers=91' . $phone . '&Message=' . rawurlencode($message));

                Log::info($response);
                return $response;
            } else {
                $otp_key = urlencode(env('SMS_TEXT_LOCAL_OTP_API_KEY'));
                return $response =  Http::get('https://api.textlocal.in/send/', [
                    'apikey' => $otp_key,
                    'numbers' => '91' . $phone,
                    'sender' => urlencode(env('SMS_SENDER_ID')),
                    'message' => $message,
                ]);
            }
        } else {
            $otp_key = urlencode(env('SMS_TEXT_LOCAL_API_KEY'));
            return $response =  Http::get('https://api.textlocal.in/send/', [
                'apikey' => $otp_key,
                'numbers' => '91' . $phone,
                'sender' => urlencode(env('SMS_SENDER_ID')),
                'message' => $message,
            ]);
        }
    }

}
