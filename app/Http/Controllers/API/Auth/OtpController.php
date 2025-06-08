<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Otp;
use App\Mail\SendOtpMail;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Workbench\App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\VerifyEmailRequest;

class OtpController extends Controller
{

    // Send OTP To the email
    public function sendOtp($email)
    {
        // Generate an OTP
        $otp = rand(1000, 9999);

        // Store the otp in the Database
        Otp::create([
            'email' => $email,
            'otp'   => $otp,
            'expires_at'    => now()->addMinutes(10),
        ]);

        // Send Email with OTP
        Mail::to($email)->Send(new SendOtpMail($otp));
    }



    // Verify Email function
    public function verifyEmail(Request $request, VerifyEmailRequest $verifyEmailRequest)
    {
        // Validate Email and OTP
        $data = $verifyEmailRequest->validated();

        // check OTP
        $otpCheck = Otp::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpCheck) {
            return ApiResponse::SendResponse(400, 'Invalid or Expired OTP', []);
        }

        // Update otp table
        $otpCheck->user = true;
        $otpCheck->save();

        // Get the User
        $user = User::where('email', $data['email'])->first();
        $user->email_verified_at = now();
        $user->save();

        // Return Response
        return ApiResponse::SendResponse(200, 'Email Verified Successfully', []);
    }


    
}
