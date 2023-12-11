<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\AppointmentService;
use App\Mail\CancelAppointment;
use App\Mail\RescheduleAppointment;
use App\Mail\SendAppointmentConfirmation;
use App\Models\Appointment;
use App\Models\Clinician;
use App\Models\Location;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{

    /**
     * Get list of appointments
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $filter = $request->get('filter');
            $searchQuery = $request->get('searchQuery');
            $todaysAppointments = Appointment::where('date', date('Y-m-d'))->where('status', '!=', 'Cancelled')->where('payment_status', 'confirmed')->count();
            $upcomingAppointments = Appointment::where('date', '>', date('Y-m-d'))->where('status', '!=', 'Cancelled')->where('payment_status', 'confirmed')->count();
            $cancelledAppointments = Appointment::where('status', 'Cancelled')->where('payment_status', 'confirmed')->count();
            $appointments = Appointment::with(['referral', 'clinician'])->where('referral_id', null);
            if ($searchQuery != "") {
                $appointments = $appointments->where('first_name', 'like', $searchQuery . '%')->orWhere('email', 'like', $searchQuery . '%')->orWhere('reference_id', 'like', $searchQuery . '%')->where('referral_id', null);
            }
            if ($filter == "0") {
                $appointments = $appointments->where('status', 'Pending')->where('payment_status', 'pending')->where('referral_id', null);
            }
            if ($filter == "1") {
                $appointments = $appointments->where('date', '>', date('Y-m-d'))->where('status', '!=', 'Cancelled')->where('status', '!=', 'Fulfilled')->where('payment_status', 'confirmed')->where('referral_id', null);
            }
            if ($filter == "2") {
                $appointments = $appointments->where('status', 'Cancelled')->where('payment_status', 'confirmed')->where('referral_id', null);
            }
            if ($filter == "3") {
                $appointments = $appointments->where('date', date('Y-m-d'))->where('status', '!=', 'Cancelled')->where('status', '!=', 'Fulfilled')->where('payment_status', 'confirmed')->where('referral_id', null);
            }
            if ($filter == "4") {
                $appointments = $appointments->where('status', 'Cancelled')->where('payment_status', 'pending')->where('referral_id', null);
            }
            if ($filter == "5") {
                $appointments = $appointments->where('payment_status', 'pending')->where('referral_id', null);
            }
            if ($filter == "6") {
                $appointments = $appointments->where('payment_status', 'confirmed')->where('referral_id', null);
            }
            if ($filter == "7") {
                $appointments = $appointments->where('status', 'Fulfilled')->where('referral_id', null);
            }
            if ($filter == "8") {
                $appointments = Appointment::with(['referral', 'clinician'])->where('referral_id', '!=', null);
            }
            if ($filter == "9") {
                $referrals = Referral::where('dr_name', 'like', $searchQuery . '%')->pluck('id')->toArray();
                $appointments = Appointment::with(['referral', 'clinician'])->where('referral_id', '!=', null)->where(function ($query) use ($searchQuery, $referrals) {
                    $query->orWhere('first_name', 'like', $searchQuery . '%')->orWhere('reference_id', 'like', $searchQuery . '%')->orWhereIn('referral_id', $referrals);
                });
            }

            $totalAppointments = $appointments->count();
            $appointments = $appointments->skip($request->get('skip'))->take(10)->orderBy('date', 'asc')->get();

            $data = [
                'todaysAppointments' => $todaysAppointments,
                'upcomingAppointments' => $upcomingAppointments,
                'totalAppointments' => $totalAppointments,
                'appointments' => $appointments,
                'cancelledAppointments' => $cancelledAppointments,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add Appointment
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        
        try {
            $validateRequest = AppointmentService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $accessToken = Common::getAzaleaAccessToken();
                $requestedData = $request->all();
                $location = Location::where('azalea_id', $request->get('location'))->first();
                $requestedData['location'] = [
                    "reference" => "Location/$location->azalea_id",
                    "type" => "Location",
                    "display" => $location->display_name,
                ];
                $createPatient = $this->createPatient($requestedData, $accessToken->access_token);
                $formattedData = json_decode($createPatient);
                Log::info('$formattedData => ', (array) $formattedData);
                if ($formattedData != "") {
                    $requestedData['patient_id'] = $formattedData ? $formattedData->id : null;
                    if (isset($requestedData['insurance_id'])) {
                        $requestedData['status'] = 'pending';
                    } else {
                        $requestedData['status'] = 'booked';
                    }
                    $createAppointment = $this->createAppointment($requestedData, $accessToken->access_token, null, 'POST');
                    if ($createAppointment['response'] != "") {
                        $appointmentData = json_decode($createAppointment['response']);
                        if ($appointmentData && $appointmentData->resourceType == "Appointment") {
                            $azaleaAppointmentId = AppointmentService::getAzaleaAppointment($requestedData['patient_id'], $accessToken->access_token);
                            $totalAppointments = Appointment::count();
                            $requestedData['reason_code'] = isset($appointmentData->reasonCode) ? serialize($appointmentData->reasonCode) : null;
                            $requestedData['start'] = $appointmentData->start;
                            $requestedData['end'] = $appointmentData->end;
                            $requestedData['reference_id'] = rand(100000, 999999) . '' . $totalAppointments;
                            $requestedData['location'] = $location->azalea_id;
                            $requestedData['status'] = isset($requestedData['insurance_id']) ? 'Pending' : 'Booked';
                            $requestedData['payment_status'] = isset($requestedData['insurance_id']) ? 'pending' : 'confirmed';
                            $requestedData['azalea_appointment_id'] = $azaleaAppointmentId;
                            $requestedData['azalea_patient_id'] = $requestedData['patient_id'];
                            AppointmentService::create($requestedData);
                            $clinician = Clinician::where('id', $requestedData['clinician_id'])->first();
                            $content = [
                                'name' => $requestedData['first_name'] . ' ' . $requestedData['last_name'],
                                'date' => $requestedData['date'],
                                'time' => $requestedData['time'],
                                'charges' => $clinician->charges_per_session,
                                'location' => $location->name,
                                'payment_mode' => $requestedData['payment_mode'],
                                'clinicianImage' => $clinician->image,
                                'clinicianName' => $clinician->name,
                                'email' => $clinician->email,
                                'qualification' => $clinician->qualification,
                                'phone_no' => $clinician->phone_no,
                            ];

                            Mail::to($requestedData['email'])->send(new SendAppointmentConfirmation($content));
                            return Common::sendResponse('Appointment has been scheduled successfully.', 200, [
                                'reference_id' => $requestedData['reference_id'],
                            ], true);
                        } else {
                            Log::info('Else case store function appointment controller');

                            return Common::sendResponse("Slot has been already booked. Please try with other slot.", 200, [], false);
                        }
                    } else {
                        Log::info('Else case store function appointment controller  response is null');

                        return Common::sendResponse("Slot is already booked. Please try with other slot.", 200, [], false);
                    }
                } else {
                    return Common::sendResponse("Slot is already booked. Please try with other slot.", 200, [], false);
                }
            } else {

                return Common::sendResponse($validateRequest['message'], 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $appointment = AppointmentService::getAppointment($id);
            if ($appointment) {

                return Common::sendResponse(null, 200, $appointment, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Cancel appointment
     *
     * @param Request $request
     * @return void
     */
    public function cancel(Request $request)
    {

        try {
            $appointment = AppointmentService::getAppointment($request->get('appointment_id'));
            if ($appointment) {
                // $accessToken = Common::getAzaleaAccessToken();
                // $requestedData = [
                //     "patient_id" => $appointment->azalea_patient_id,
                //     "first_name" => $appointment->first_name,
                // ];
                // $payload = [
                //     "resourceType" => "Appointment",
                //     "id" => $appointment->azalea_appointment_id,
                //     "status" => "cancelled",
                //     "cancellationDate" => "2023-10-25",
                // "participant" => [
                //     [
                //         "actor" => [
                //             "reference" => "Patient/388833",
                //             "type" => "Patient",
                //             "display" => "random clinician",
                //         ],
                //         "status" => "needs-action",
                //     ]
                // ],
                // ];

                // $url = "https://app.azaleahealth.com/fhir/R4/142114/Appointment/" . $appointment->azalea_appointment_id;
                // Log::info('Payload for appointment cancel on azalea ==> ', (array) $payload);
                // $appointment = Common::handleCurlPostRequest($url, $accessToken->access_token, $payload, "PATCH");
                // Log::info('Log for cancel function ==> ', $appointment);
                // dd($appointment);
                $requestedData = $request->all();
                AppointmentService::cancelAppointment($requestedData, $appointment);

                return Common::sendResponse('Appointment has been cancelled.', 200, $appointment, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Function to create patient in azalea
     *
     * @param [type] $requestedData
     * @param [type] $accessToken
     * @return void
     */
    public function createPatient($requestedData, $accessToken)
    {
        $payload = [
            "resourceType" => "Patient",
            "active" => true,
            "name" => [
                [
                    "use" => "usual",
                    "text" => $requestedData['first_name'],
                    "family" => $requestedData['last_name'],
                    "given" => [
                        $requestedData['first_name'],
                    ],
                ],
            ],
            "telecom" => [
                [
                    "system" => "email",
                    "value" => $requestedData['email'],
                    "rank" => 1,
                ],
                [
                    "system" => "phone",
                    "value" => $requestedData['home_phone'],
                    "use" => "home",
                ],
                [
                    "system" => "phone",
                    "value" => $requestedData['mobile_number'],
                    "use" => "mobile",
                ],
            ],
            "birthDate" => $requestedData['dob'],
        ];

        $url = "https://app.azaleahealth.com/fhir/R4/142114/Patient";
        $createPatientRequest = Common::handleCurlPostRequest($url, $accessToken, $payload);

        return $createPatientRequest['response'];
    }

    /**
     * Function to create appointment in azalea
     *
     * @param [type] $requestedData
     * @param [type] $accessToken
     * @return void
     */
    public function createAppointment($requestedData, $accessToken, $appointmentId = null, $method = "POST")
    {
        $payload = [
            "resourceType" => "Appointment",
            "status" => $requestedData['status'],
            "start" => $requestedData['start_date_time'],
            "end" => $requestedData['end_date_time'],
            "participant" => [
                [
                    "actor" => $requestedData['location'],
                    "status" => "accepted",
                ],
                [
                    "actor" => [
                        "reference" => "Patient/" . $requestedData['patient_id'],
                        "type" => "Patient",
                        "display" => $requestedData['first_name'],
                    ],
                    "status" => "accepted",
                ],
                [
                    "actor" => [
                        "reference" => "Practitioner/" . $requestedData['practitioner_id'],
                        "type" => "Practitioner",
                        "display" => $requestedData['practitioner_name'],
                    ],
                    "status" => "accepted",
                ],
            ],
        ];
        if (isset($requestedData['appointmentType']) && sizeof($requestedData['appointmentType'])) {
            $payload['reasonCode'] = [$requestedData['appointmentType']];
        }
        if ($appointmentId) {
            $payload['id'] = $appointmentId;
            $url = "https://app.azaleahealth.com/fhir/R4/142114/Appointment/" . $appointmentId;
        } else {
            $url = "https://app.azaleahealth.com/fhir/R4/142114/Appointment";
        }

        Log::info('Log for createAppointment function payload ==> ', $payload);
        $appointment = Common::handleCurlPostRequest($url, $accessToken, $payload, $method);
        Log::info('Log for createAppointment function ==> ', $appointment);
        return $appointment;
    }

    /**
     * Reschedule appointment function
     *
     * @param Request $request
     * @return void
     */
    public function rescheduleAppointment(Request $request)
    {
        try {
            $appointment = AppointmentService::getAppointment($request->get('appointment_id'));
            if ($appointment) {
                $accessToken = Common::getAzaleaAccessToken();
                $location = Location::where('azalea_id', $appointment->location)->first();
                $requestedDataLocation = [
                    "reference" => "Location/$location->azalea_id",
                    "type" => "Location",
                    "display" => $location->display_name,
                ];

                $requestedData = [
                    "start_date_time" => $appointment->start,
                    "end_date_time" => $appointment->end,
                    "location" => $requestedDataLocation,
                    "patient_id" => $appointment->azalea_patient_id,
                    "first_name" => $appointment->first_name,
                    "practitioner_id" => $appointment->clinician->azalea_id,
                    "practitioner_name" => $appointment->clinician->name,
                    "appointmentType" => $request->get('appointmentType'),
                    "status" => "cancelled",
                ];

                $cancelAppointment = $this->createAppointment($requestedData, $accessToken->access_token, $appointment->azalea_appointment_id, 'PUT');

                $requestedData['status'] = "booked";
                $requestedData['start_date_time'] = $request->get('start_date_time');
                $requestedData['end_date_time'] = $request->get('end_date_time');
                $createAppointment = $this->createAppointment($requestedData, $accessToken->access_token, null, 'POST');

                if ($createAppointment['response'] != "") {
                    $appointmentData = json_decode($createAppointment['response']);

                    if ($appointmentData && $appointmentData->resourceType == "Appointment") {
                        $azaleaAppointmentId = AppointmentService::getAzaleaAppointment($appointment->azalea_patient_id, $accessToken->access_token);
                        $appointment->update([
                            'date' => $request->get('date'),
                            'time' => $request->get('time'),
                            'azalea_appointment_id' => $azaleaAppointmentId,
                            'start' => $appointmentData->start,
                            'end' => $appointmentData->end,
                        ]);

                        $content = [
                            'name' => $appointment->first_name . ' ' . $appointment->last_name,
                            'date' => $appointment->date,
                            'time' => $appointment->time,
                            'charges' => $appointment->clinician ? $appointment->clinician->charges_per_session : 0,
                            'location' => $location->name,
                            'payment_mode' => $appointment->payment_mode,
                            'clinicianImage' => $appointment->clinician ? $appointment->clinician->image : null,
                            'clinicianName' => $appointment->clinician ? $appointment->clinician->name : null,
                            'email' => $appointment->clinician ? $appointment->clinician->email : null,
                            'qualification' => $appointment->clinician ? $appointment->clinician->qualification : null,
                            'phone_no' => $appointment->clinician ? $appointment->clinician->phone_no : null,
                        ];

                        Mail::to($appointment->email)->send(new RescheduleAppointment($content));

                        return Common::sendResponse('Re-scheduled successfully.', 200, [], true);
                    } else {

                        return Common::sendResponse("Slot is already booked. Please try with other slot.", 200, [], false);
                    }

                } else {
                    Log::info('Else case rescheduleAppointment function');

                    return Common::sendResponse("Slot is already booked. Please try with other slot.", 200, [], false);
                }
            } else {

                return Common::sendResponse("Appointment not found.", 200, [], false);
            }

        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        try {
            $appointment = AppointmentService::getAppointment($request->get('appointment_id'));
            if ($appointment) {
                $accessToken = Common::getAzaleaAccessToken();
                $location = Location::where('azalea_id', $appointment->location)->first();
                $requestedDataLocation = [
                    "reference" => "Location/$location->azalea_id",
                    "type" => "Location",
                    "display" => $location->display_name,
                ];

                $requestedData = [
                    "start_date_time" => $appointment->start,
                    "end_date_time" => $appointment->end,
                    "location" => $requestedDataLocation,
                    "patient_id" => $appointment->azalea_patient_id,
                    "first_name" => $appointment->first_name,
                    "practitioner_id" => $appointment->clinician->azalea_id,
                    "practitioner_name" => $appointment->clinician->name,
                    "status" => lcfirst($appointment->status),
                ];

                if ($appointment->reason_code) {
                    $requestedData["appointmentType"] = unserialize($appointment->reason_code);
                }

                $updateAppointment = $this->createAppointment($requestedData, $accessToken->access_token, $appointment->azalea_appointment_id, 'PUT');

                $appointment->update([
                    'status' => $request->get('status'),
                    'payment_status' => $request->get('status') == "booked" ? "confirmed" : $appointment->payment_status,
                    'cancellation_reason' => $request->get('cancellation_reason'),
                    'payment_method' => $request->get('payment_mode') != "" && $request->get('payment_mode') != "null" ? $request->get('payment_mode') : null,
                ]);

                $content = [
                    'clinicianName' => $appointment->clinician ? $appointment->clinician->name : null,
                    'date' => $appointment->date,
                    'time' => $appointment->time,
                    'name' => $appointment->first_name . ' ' . $appointment->last_name,
                    'reference_no' => $appointment->reference_id,
                    'reason' => $appointment->cancellation_reason,
                ];

                Mail::to($appointment->email)->send(new CancelAppointment($content));

                return Common::sendResponse('Status updated successfully.', 200, [], true);
            } else {

                return Common::sendResponse("Appointment not found.", 200, [], false);
            }

        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function getPatientAppointment(Request $request)
    {
        try {
            $nameArray = explode(' ', $request->get('name'));
            $appointment = Appointment::where([
                'email' => $request->get('email'),
                'first_name' => $nameArray[0],
                'status' => 'Booked',
                'payment_status' => 'confirmed',
            ])->with('clinician', 'serviceProvided', 'locationData', 'insurance', 'plan', 'referral')->orderBy('created_at', 'desc')->first();
            if ($appointment) {

                return Common::sendResponse('', 200, $appointment, true);
            } else {

                return Common::sendResponse("Appointment not found.", 200, [], false);
            }

        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
