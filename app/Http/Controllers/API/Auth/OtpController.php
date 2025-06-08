<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    

    public function sendOtp( $email )
    {
        // Generate an OTP
        $otp = rand(1000,9999);

        // Store the otp in the Database
        Otp::create([
            'email' => $email,
            'otp'   => $otp,
            'expires_at'    => now()->addMinutes(10),
        ]);

        // Send Email with OTP
        // ---
    }

}
