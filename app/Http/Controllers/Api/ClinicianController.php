<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\ClinicianService;
use App\Models\Clinician;
use App\Models\ClinicianLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClinicianController extends Controller
{

    /**
     * Get list of clinicians
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            ini_set('max_execution_time', 800);

            if ($request->has('state')) {
                $q = Clinician::with(['ageCovered', 'specialties', 'states', 'servicesProvided', 'locations', 'ageCovered.ageRange', 'locations.location']);
                if ($request->get('state') != "" && $request->get('state') != "null") {
                    $state = $request->get('state');
                    $q->whereHas('states', function ($q) use ($state) {
                        $q->where('state_id', $state);
                    });
                }

                if ($request->get('speciality') != "" && $request->get('speciality') != "null") {
                    $speciality_id = $request->get('speciality');
                    $q->whereHas('specialties', function ($q) use ($speciality_id) {
                        $q->where('speciality_id', $speciality_id);
                    });
                }

                if ($request->get('age-range') != "" && $request->get('age-range') != "null") {
                    $age_range_id = $request->get('age-range');
                    $q->whereHas('ageCovered', function ($q) use ($age_range_id) {
                        $q->where('age_range_id', $age_range_id);
                    });
                }

                if ($request->get('services-provided') != "" && $request->get('services-provided') != "null") {
                    $service_provided_id = $request->get('services-provided');
                    $q->whereHas('servicesProvided', function ($q) use ($service_provided_id) {
                        $q->where('service_provided_id', $service_provided_id);
                    });
                }
                $isInsurance = $request->get('isInsurance');
                if ($isInsurance != "" && $isInsurance != "null" && $isInsurance == "insurance") {
                    $q->where('is_insurance', 1);
                }

                $locationId = $request->get('location');
                if ($request->get('location') != "" && $request->get('location') != "null") {
                    $locationId = $request->get('location');
                    $locationIdArr = ClinicianLocation::where('azalea_id', $locationId)->pluck('clinician_id')->toArray();
                    $q->whereIn('id', $locationIdArr);
                    $q->with(['ageCovered', 'specialties', 'states', 'servicesProvided', 'ageCovered.ageRange', 'locations' => function ($query) use ($locationId) {
                        $query->where('azalea_id', $locationId);
                    }, 'locations.location']);

                } else {
                    $q->with(['ageCovered', 'specialties', 'states', 'servicesProvided', 'locations', 'ageCovered.ageRange', 'locations.location']);
                }

                $accessToken = Common::getAzaleaAccessToken();
                $cliniciansCount = $q->where('status', 1)->get()->count();
                $clinicians = $q->where('status', 1)->skip($request->get('skip'))->take(10)->get();
                $locId = $locationId;
                $currentDate = date("Y-m-d");
                if ($request->get('date') != "" && $request->get('date') != "null") {
                    $currentDate = $request->get('date');
                }

                foreach ($clinicians as $clinicianKey => $clinician) {
                    foreach ($clinician->locations as $key => $location) {
                        $locationId = $location->azalea_id;
                        $slots = [];
                        for ($i = 0; $i < 60; $i++) {

                            $dateNeedToFind = $i;

                            if (sizeof($slots) < 1) {
                                $nextDate = date("Y-m-d", strtotime("$currentDate +$dateNeedToFind day"));
                                $url = "https://app.azaleahealth.com/fhir/R4/142114/Slot?start=$nextDate&schedule.actor=Practitioner/$clinician->azalea_id&schedule.actor=Location/$locationId&status=free&_count=7";

                                Log::info($url);
                                $curlRequest = Common::handleCurlGetRequest($url, $accessToken->access_token);
                                $decodedData = json_decode($curlRequest['response'], true);

                                if (isset($decodedData['error'])) {
                                    dd("error => ", $decodedData['error']);
                                } else {
                                    if (sizeof($decodedData) && isset($decodedData['entry'])) {
                                        foreach ($decodedData['entry'] as $decodedRecord) {
                                            $resource = $decodedRecord['resource'];
                                            $slots[] = [
                                                "date" => date("D M d, h:ia", strtotime($resource['start'])),
                                                "start" => $resource['start'],
                                                "id" => $resource['id'],
                                                "status" => $resource['status'],
                                                "url" => $url,
                                                "appointmentType" => isset($resource['appointmentType']) ? $resource['appointmentType'] : [],
                                                "formatted_date" => date("Y-m-d", strtotime($resource['start'])),
                                                "start_time" => date("h:i", strtotime($resource['start'])),
                                                "end_time" => date("h:i", strtotime($resource['end'])),
                                                "start_date_time" => $resource['start'],
                                                "end_date_time" => $resource['end'],
                                            ];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                break;
                            }
                            // }
                        }
                        $location->slots = $slots;
                        $clinicians[$clinicianKey]['slots'] = $slots;
                    }
                }
            } else {
                $cliniciansCount = Clinician::all()->count();
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $clinicians = Clinician::where('name', 'like', $search . '%')->orWhere('azalea_id', 'like', $search . '%')->orWhere('tru_billing_id', 'like', $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $clinicians = Clinician::skip($request->get('skip'))->take(10)->get();
                }
            }
            $data = [
                'data' => $clinicians,
                'totalRecords' => $cliniciansCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add Clinician
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = ClinicianService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                if ($request->file()) {
                    $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');

                    $name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['image'] = $path;
                }
                ClinicianService::create($requestedData);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get data of clinician
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $fetchSlots = $request->has('slots') ? true : false;
            $locationId = $request->get('location');
            $accessToken = $fetchSlots == true ? Common::getAzaleaAccessToken() : null;
            $user = ClinicianService::getUser($id, $accessToken ? $accessToken->access_token : null, $fetchSlots, $locationId);
            if ($user) {
                $user->is_insurance = $user->is_insurance == 1 ? "1" : "0";
                return Common::sendResponse(null, 200, $user, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update clinician
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $user = ClinicianService::getUser($id, null, false);
            if ($user) {
                unset($user->insurance_names);
                $requestedData = $request->all();
                if ($request->hasFile('image')) {
                    $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
                    $path = '/storage/' . $file_path;
                    $requestedData['image'] = $path;
                } else {
                    $requestedData['image'] = $user->image;
                }

                $validateRequest = ClinicianService::update($requestedData, $user);
                if ($validateRequest['status'] === true) {

                    return Common::sendResponse('Updated successfully.', 200, $user, true);
                } else {
                    return Common::sendResponse($validateRequest['message'], 400, [], false);
                }
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update status of clinician
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = ClinicianService::getUser($id, null, false);
            if ($user) {
                unset($user->insurance_names);
                $user->update([
                    'status' => $request->status,
                ]);

                return Common::sendResponse('Updated successfully.', 200, $user, true);

            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete clinician
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $user = ClinicianService::getUser($id, null, false, null);
            if ($user) {
                ClinicianService::delete($user);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple clinicians
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            ClinicianService::deleteMultiple($request->get('clinicians'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
