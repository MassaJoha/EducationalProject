<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOtpRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $authService;
    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function signup(StoreUserRequest $request){
        // Call the AuthService to handle signup
        $this->authService->signup($request, $request['name'], $request['email']);

        // Return a response
        return response()->json(['message' => 'Verification email sent']);
    }

    public function verify($otp){
        // Call the AuthService to handle verfiy function
        $user = $this->authService->verify($otp);
        if($user){

            // Return a response
            return response()->json(['message' => 'Email verified successfully!']);
        }

        // Return an error response if the otp is invalid
        return response()->json(['error' => 'Invalid verification otp'], 400);
    }
    
    public function login(Request $request){
        try{
            // Check if user email is exist or not
            $userExists = User::where('email', $request['email'])->exists();

            if ($userExists) {
                // Call the AuthService to handle login
                $this->authService->login($request->email);

                // Return a response
                return response()->json(['message' => 'OTP sent to email']);
            }
        }catch(Exception $e){

            return response()->json($e->getMessage(), 400);
        }
    }

    public function authenticate(StoreOtpRequest $request){
        // Call the AuthService to handle authentication
        $otp = $this->authService->authenticate($request['email'], $request['otp']);

        // Return the API otp as the response
        return response()->json(['otp' => $otp]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        // Return a response
        return response()->json(['message' => 'Logged out successfully']);
    }
}
