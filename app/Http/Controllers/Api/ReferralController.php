<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        try {
            $referrals = Referral::all();

            return Common::sendResponse(null, 200, $referrals, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    public function store(Request $request)
    {
        try {
            $requestedData = $request->all();
            $referral = Referral::create($requestedData);

            return Common::sendResponse('Submitted successfully.', 200, $referral, true);

        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
