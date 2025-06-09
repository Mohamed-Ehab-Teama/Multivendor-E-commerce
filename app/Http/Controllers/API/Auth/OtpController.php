<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\Otp;
use App\Models\User;
use App\Mail\SendOtpMail;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\VerifyEmailRequest;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Return_;

class OtpController extends Controller
{

    // Send OTP To verify email
    public function sendEmailVerificationOtp(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:users,email',
        ]);

        // Generate an OTP
        $otp = rand(1000, 9999);

        // Store the otp in the Database
        Otp::create([
            'email'         => $request->email,
            'otp'           => $otp,
            'expires_at'    => now()->addMinutes(10),
        ]);

        // Send Email with OTP
        Mail::to($request->email)->Send(new SendOtpMail($otp));

        return ApiResponse::SendResponse(200, "OTP Sent Successfully", []);
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
        $otpCheck->used = true;
        $otpCheck->save();

        // Get the User
        $user = User::where('email', $data['email'])->first();
        $user->email_verified_at = now();
        $user->save();

        // Return Response
        return ApiResponse::SendResponse(200, 'Email Verified Successfully', []);
    }



    // Send Rest-Password OTP
    public function sendResetPasswordOtp(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:users,email',
        ]);

        $otp = rand(1000,9999);

        Otp::create([
            'email'         => $request->email,
            'otp'           => $otp,
            'expires_at'    => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new SendOtpMail($otp));

        return ApiResponse::SendResponse(200, "OTP Sent Successfully", []);
    }
    


    // Reset Password
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $otpCheck = Otp::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpCheck)
        {
            return ApiResponse::SendResponse(400, "Otp Invalid or Expired", []);
        }

        $otpCheck->used = true;
        $otpCheck->save();

        $user = User::where('email', $data['email'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();

        return ApiResponse::SendResponse(200, 'New Password Set Successfully', []);
    }
}
