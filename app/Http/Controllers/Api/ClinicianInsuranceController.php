<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\ClinicianInsurance;
use Illuminate\Http\Request;

class ClinicianInsuranceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $clinicianId = $request->get('clinician_id');
            $insurances = ClinicianInsurance::where('clinician_id', $clinicianId)->with('insurance')->get()->groupBy('insurance_id');

            return Common::sendResponse(null, 200, $insurances, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    public function getPlans(Request $request, $insuranceId)
    {
        try {
            $clinicianId = $request->get('clinician_id');
            $insurancePlans = ClinicianInsurance::where('clinician_id', $clinicianId)->where('insurance_id', $insuranceId)->with('plan')->get();
            $plans = [];
            foreach ($insurancePlans as $key => $plan) {
                if ($plan->plan_id) {
                    $plans[] = $plan;
                }
            }

            return Common::sendResponse(null, 200, $plans, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
