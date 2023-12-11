<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\TruBillingInsurance;
use Illuminate\Http\Request;

class TruBillingInsuranceController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $allInsuranceCount = TruBillingInsurance::all()->count();
            $insurances = TruBillingInsurance::all();

            $data = [
                'data' => $insurances,
                'totalRecords' => $allInsuranceCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
