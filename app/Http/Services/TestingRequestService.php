<?php

namespace App\Http\Services;

use App\Models\TestingRequest;
use Illuminate\Support\Facades\Validator;

class TestingRequestService
{

    /**
     * Undocumented function
     *
     * @param [array] $requestedData
     * @return void
     */
    public static function validateRequest($requestedData)
    {
        $validator = Validator::make($requestedData, [
            'first_name' => 'required|string|min:3',
            'email' => 'required|email',
            'home_phone' => 'required',
            'mobile_number' => 'required',
            'dob' => 'required',
            'testing_type_id' => 'required',
            'insurance_or_selfpay' => 'required|string',
        ]);

        return [
            'message' => $validator->fails() ? $validator->messages()->first() : null,
            'status' => $validator->fails() ? false : true,
        ];
    }

    /**
     * Create testingRequest
     *
     * @param [type] $requestedData
     * @return void
     */
    public static function create($requestedData)
    {
        $testingRequest = TestingRequest::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getTestingRequest($id)
    {
        $testingRequest = TestingRequest::where('id', $id)->with('testingType', 'insurance', 'plan')->first();

        return $testingRequest;
    }

}
