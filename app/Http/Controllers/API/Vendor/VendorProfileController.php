<?php

namespace App\Http\Controllers\API\Vendor;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\UpdateVendorProfileRequest;
use Illuminate\Http\Request;

class VendorProfileController extends Controller
{
    /**
     * Get Profile Data || Show Profile
     */
    public function showVendorProfile(Request $request)
    {
        $prodileData = $request->user()->vendorProfile;
        if ($prodileData) {
            return ApiResponse::SendResponse(200, 'Profile Data Retrieved Successfully', $prodileData);
        }
        return ApiResponse::SendResponse(404, 'No User Data Found', []);
    }



    /**
     * Update Vendor Profile 
     */
    public function storeOrUpdateVendorProfile(Request $request, UpdateVendorProfileRequest $updateVendorProfileRequest)
    {
        $profileData = $updateVendorProfileRequest->validated();

        $vendor = $request->user()->vendorProfile;

        if ($vendor)
        {
            $vendor->update( $profileData );
        }
        else
        {
            $vendor = $request->user()->vendorProfile()->create($profileData);
        }

        return ApiResponse::SendResponse(200, "Profile Saved Successfully", $vendor);
    }
}
