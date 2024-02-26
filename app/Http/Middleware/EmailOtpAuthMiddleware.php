<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Mail;

class EmailOtpAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the request contains a valid OTP
        $otp = $request->header('X-OTP');
        if ($otp && $this->isValidOtp($otp)) {
            // Proceed to the next middleware or route handler
            return $next($request);
        }

        // Generate and send the OTP to the user's email
        $otp = $this->generateOtp();
        $this->sendOtpToEmail($otp, $request->user()->email);

        // Return a response indicating that the OTP has been sent
        return response()->json(['message' => 'OTP has been sent to your email'], 200);
    }

    private function isValidOtp($otp)
    {
        // Implement your OTP validation logic here
        // You can validate the OTP against a database record or any other source
        
        // Example: Check if the OTP matches the one stored in the user's session
        return $otp === session('otp');
    }

    private function generateOtp()
    {
        // Generate a random OTP (e.g., 6-digit numeric code)
        $otp = str_random(6);
        // Store the OTP in the user's session for later validation
        session(['otp' => $otp]);

        return $otp;
    }

    private function sendOtpToEmail($otp, $email)
    {
        // Send the OTP to the user's email
        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('OTP Verification');
        });
    }
}
