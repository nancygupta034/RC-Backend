<?php

namespace App\Http\Services;

use App\Helpers\Common;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentService
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
            // 'date' => 'required',
            // 'time' => 'required',
            'service_id' => 'required',
            // 'relation_to_patient' => 'required',
            // 'hear_about_us' => 'required|string',
        ]);

        return [
            'message' => $validator->fails() ? $validator->messages()->first() : null,
            'status' => $validator->fails() ? false : true,
        ];
    }

    /**
     * Create clinician
     *
     * @param [type] $requestedData
     * @return void
     */
    public static function create($requestedData)
    {
        $clinician = Appointment::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getAppointment($id)
    {
        $appointment = Appointment::where('id', $id)->orWhere('reference_id', $id)->with('clinician', 'serviceProvided', 'locationData', 'insurance', 'plan', 'referral')->first();

        return $appointment;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $appointment
     * @return void
     */
    public static function cancelAppointment($requestedData, $appointment)
    {
        $appointment->update([
            'cancellation_reason' => $requestedData['cancellation_reason'],
            'status' => 'Cancelled',
        ]);

        return $appointment;
    }

    /**
     * Undocumented function
     *
     * @param [type] $patientId
     * @param [type] $accessToken
     * @return void
     */
    public static function getAzaleaAppointment($patientId, $accessToken)
    {
        $url = "https://app.azaleahealth.com/fhir/R4/142114/Appointment?actor=Patient/$patientId";
        $appointments = Common::handleCurlGetRequest($url, $accessToken);
        $appointmentId = null;
        $appointmentData = json_decode($appointments['response']);
        $appointments = $appointmentData->entry;
        if (count($appointments) > 0) {
            $currentAppointment = $appointments[count($appointments) - 1];
            if ($currentAppointment->resource && $currentAppointment->resource->id) {
                $appointmentId = $currentAppointment->resource->id;
            }
        }
        Log::info('Log for getAzaleaAppointment function ');

        return $appointmentId;
    }

}
