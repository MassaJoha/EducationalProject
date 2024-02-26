<?php
namespace App\Services;

use App\Events\AfterUserLoginOrSignupEvent;
use App\Events\FirstEvent;
use App\Http\Requests\StoreUserRequest;
use App\Mail\OtpMail;
use App\Mail\VerificationEmail;
use App\Models\OtpAttemp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Contract\Database;

class AuthService
{
    public function signup(StoreUserRequest $request, $name, $email){
        // Generate a verification otp
        $otp = str_random(6);

         // Put and exapiry time for otp
         $expiry = now()->addMinutes(30);

        // Save the otp and email in the database
        $otpAttemp = OtpAttemp::create([
            'email'      => $email,
            'otp'        => $otp,
            'otp_expiry' => $expiry,
        ]);

        // Save the name and email in the database
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->otp_expiry = $expiry;
        $user->save();
        
        // Fire the event, that sending SMS using JSON placeholder website
        $title = 'Hello '. $email;
        $body = 'You are now signingup successfully!';
        $userId = $user->id; 
        event(new FirstEvent($title, $body, $userId));

        // Send the verification email
        Mail::to($email)->send(new VerificationEmail($otp));

        // Fire the event, that sending email after signup
        event(new AfterUserLoginOrSignupEvent($otpAttemp));
    }

    public function verify($otp)
    {
       // Find the verification otp in the database
       $otpAttemp = OtpAttemp::where('otp', $otp)->first();

       if($otpAttemp) {
           // Mark the email as verified in the database
           $user = User::where('email', $otpAttemp->email)->first();
           $user->update(['email_verified_at' => now()]);

           // Return a response
           return response()->json(['message' => 'Email verified successfully']);
        }
    }

    public function login($email){
        // Generate a new OTP
        $otp = str_random(6);

        // Put and exapiry time for otp
        $expiry = now()->addMinutes(30);

        // Save the OTP in the database
        $otpAttemp = OtpAttemp::create([
            'email'      => $email,
            'otp'        => $otp,
            'otp_expiry' => $expiry,
        ]);

        // Fire the event, that sending SMS using JSON placeholder website
        $title = 'Hello '. $email;
        $body = 'You are now logging in successfully!';
        $userId = $otpAttemp->id;
        event(new FirstEvent($title, $body, $userId));

        // Send the OTP via email
        Mail::to($email)->send(new OtpMail($otp));

        // Fire the event, that sending email after login
        event(new AfterUserLoginOrSignupEvent($otpAttemp));  
    }

    public function authenticate($email, $otp)
    {
        // Find the verification otp in the database
        $otpAttemp = OtpAttemp::where('email', $email)->where('otp', $otp)->first();

        if ($otpAttemp) {
            // Generate an API otp for the user
            $user = User::where('email', $otpAttemp->email)->first();
            $otp = $user->createToken('API OTP')->plainTextToken;

            return $otp;
        } else {

            // Throw an exception or handle invalid OTP case
            return response()->json(['error'=>'Invalid OTP!']);
        }
    }
}