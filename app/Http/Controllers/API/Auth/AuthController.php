<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // =======================  register    ============================== //
    public function register(Request $request, RegisterRequest $registerRequest)
    {
        // Validate Registered Data
        $data = $registerRequest->validated();

        // Create User
        $user = User::create($data);

        // Assign Role To Registered User
        $user->assignRole($data['role']);

        // Generate Token
        $token = $user->createToken('autnToken')->plainTextToken;

        $returnData = [
            'token'     => $token,
            'user'      => $user,
        ];
        return ApiResponse::SendResponse(200, "User Created Successfully", $returnData);
    }



    // =======================  Login    ============================== //
    public function login(Request $request, LoginRequest $loginRequest)
    {
        // Validated Data
        $data = $loginRequest->validated();

        // Get User
        $user = User::where('email', $data['email'])->first();

        // Check if the user data is correct:
        if ( !$user || !Hash::check( $data['password'], $user->password ) )
        {
            return ApiResponse::SendResponse(401, "Invalid Credentials", []);
        }

        // Generate Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Response
        $returnData = [
            'token'     => $token,
            'user'      => $user,
        ];

        return ApiResponse::SendResponse(200, "Logged In Successfully", $returnData);
    }



    // =======================  Log Out    ============================== //
    public function logout(Request $request)
    {
        // Delete All User Tokens
        $request->user()->tokens()->delete();

        // Response
        return ApiResponse::SendResponse(200, "Logged Out Successfully", []);
    }

}
