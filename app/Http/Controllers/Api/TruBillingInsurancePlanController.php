<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\TruBillingInsurancePlan;
use Illuminate\Http\Request;

class TruBillingInsurancePlanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $allInsuranceCount = TruBillingInsurancePlan::all()->count();
            $insuranceId = $request->get('insurance_id');
            $insurances = TruBillingInsurancePlan::where('tru_billing_insurance_id', $insuranceId)->with('plan')->get();

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
