<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Http\CurlClient;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $request->validate([
            'to' => ['required', 'string', 'regex:/^\+\d{10,15}$/'], // E.164 format validation
            'message' => 'required|string',
        ]);

        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_NUMBER');  // Must be your Twilio number, e.g. +1234567890

        $to = $request->to;

        // Validate 'from' number presence
        if (!$from) {
            return response()->json(['error' => 'Twilio "from" number is not configured.'], 500);
        }

        // Validate that 'from' is not the same as 'to'
        if ($to === $from) {
            return response()->json(['error' => '"To" and "From" numbers cannot be the same.'], 400);
        }

        // Set path to your CA cert bundle for SSL verification
        $certPath = base_path('storage/certs/cacert.pem');

        $curlOptions = [
            CURLOPT_CAINFO => $certPath,
        ];

        $twilioHttpClient = new CurlClient($curlOptions);

        $client = new Client($sid, $token, null, null, $twilioHttpClient);

        try {
            // Send SMS to any valid phone number using your Twilio number as sender
            $client->messages->create($to, [
                'from' => $from,
                'body' => $request->message
            ]);

            return response()->json(['success' => 'Message sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
