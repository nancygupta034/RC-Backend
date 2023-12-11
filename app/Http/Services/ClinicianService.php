<?php

namespace App\Http\Services;

use App\Helpers\Common;
use App\Models\Clinician;
use App\Models\ClinicianAgeCovered;
use App\Models\ClinicianInsurance;
use App\Models\ClinicianLocation;
use App\Models\ClinicianServiceProvided;
use App\Models\ClinicianSpecialty;
use App\Models\ClinicianState;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClinicianService
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
            'name' => 'required|string|min:3',
            // 'email' => 'required|unique:clinicians,email,NULL,NULL,deleted_at,NULL|email',
            'qualification' => 'required|string|min:3',
            'bio' => 'required|string|min:50',
            'azalea_id' => ['required', 'string', 'min:3'],
            'tru_billing_id' => ['required', 'string', 'min:3'],
            // 'azalea_id' => 'required|numeric|min:3|unique:clinicians,azalea_id,NULL,NULL,deleted_at,NULL',
            // 'tru_billing_id' => 'required|string|min:3|unique:clinicians,tru_billing_id,NULL,NULL,deleted_at,NULL',
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
        $clinician = Clinician::create(array_merge($requestedData, [
            'phone_no' => (($requestedData['phone_no'] != null && $requestedData['phone_no'] != 'null') ? $requestedData['phone_no'] : null),
            'email' => (($requestedData['email'] != null && $requestedData['email'] != 'null') ? $requestedData['email'] : null),
        ]));
        if ($clinician) {
            $slug = Str::slug($requestedData['name']);
            $slugAlreadyExists = Clinician::where('slug', $slug)->first();
            if ($slugAlreadyExists) {
                $slug = $slug . '-' . $clinician->id;
            }
            $clinician->update([
                'slug' => $slug,
            ]);
            $specialties = [];
            $specialityIds = explode(',', $requestedData['specialities']);
            foreach ($specialityIds as $key => $speciality) {
                $specialties[] = [
                    'clinician_id' => $clinician->id,
                    'speciality_id' => $speciality,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ];
            }
            ClinicianSpecialty::insert($specialties);

            $ageRanges = [];
            $ageRangeIds = explode(',', $requestedData['ages_covered']);
            foreach ($ageRangeIds as $key => $ageRange) {
                $ageRanges[] = [
                    'clinician_id' => $clinician->id,
                    'age_range_id' => $ageRange,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ];
            }
            ClinicianAgeCovered::insert($ageRanges);

            $locations = [];
            $locationIds = explode(',', $requestedData['locations']);
            foreach ($locationIds as $key => $location) {
                $locationData = Location::where('id', $location)->first();
                if ($locationData) {
                    $locations[] = [
                        'clinician_id' => $clinician->id,
                        'location_id' => $location,
                        'azalea_id' => $locationData->azalea_id,
                        'created_at' => date('Y-m-d h:i:s'),
                        'updated_at' => date('Y-m-d h:i:s'),
                    ];
                }
            }
            ClinicianLocation::insert($locations);

            $states = [];
            $stateIds = explode(',', $requestedData['states']);
            foreach ($stateIds as $key => $state) {
                $states[] = [
                    'clinician_id' => $clinician->id,
                    'state_id' => $state,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ];
            }
            ClinicianState::insert($states);

            $services = [];
            $serviceIds = explode(',', $requestedData['services']);
            foreach ($serviceIds as $key => $service) {
                $services[] = [
                    'clinician_id' => $clinician->id,
                    'service_provided_id' => $service,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ];
            }
            ClinicianServiceProvided::insert($services);
        }

        return true;
    }

    /**
     * Get data of clinician
     *
     * @param [type] $id
     * @return void
     */
    public static function getUser($value, $token = null, $fetchSlots = true, $locationId = null)
    {
        $clinician = Clinician::where('id', $value)->orWhere('slug', $value)->with(['ageCovered', 'specialties', 'states', 'servicesProvided', 'ageCovered.ageRange', 'locations', 'locations.location', 'specialties.speciality', 'states.state', 'servicesProvided.serviceProvided', 'insurances'])->first();
        if ($clinician) {
            $currentDate = date("Y-m-d");
            $maxDays = 7;
            if ($fetchSlots == true) {
                foreach ($clinician->locations as $key => $location) {
                    $locationId = $location->azalea_id;
                    $slots = [];
                    for ($i = 0; $i < 60; $i++) {
                        $dateNeedToFind = $i;
                        if (sizeof($slots) < 1) {
                            $nextDate = date("Y-m-d", strtotime("$currentDate +$dateNeedToFind day"));
                            $url = "https://app.azaleahealth.com/fhir/R4/142114/Slot?start=$nextDate&schedule.actor=Practitioner/$clinician->azalea_id&schedule.actor=Location/$locationId&status=free&_count=7";
                            Log::info($url);
                            $curlRequest = Common::handleCurlGetRequest($url, $token);
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

                }
            }

            $insurances = ClinicianInsurance::where('clinician_id', $clinician->tru_billing_id)->with('insurance')->get()->groupBy('insurance_id');
            $clinician->insurance_names = $insurances;

        }

        return $clinician;
    }

    /**
     * Update clinician
     *
     * @param [type] $requestedData
     * @param [type] $clinician
     * @return void
     */
    public static function update($requestedData, $clinician)
    {
        $validator = Validator::make($requestedData, [
            'name' => 'required|string|min:3',
            'qualification' => 'required|string|min:3',
            'bio' => 'required|string|min:50',
            // 'email' => ['required', 'email', Rule::unique('clinicians')->ignore($clinician->id)],
            'azalea_id' => ['required', 'string', 'min:3'],
            'tru_billing_id' => ['required', 'string', 'min:3'],
            // 'azalea_id' => ['required','string','min:3',Rule::unique('clinicians')->ignore($clinician->id)],
            // 'tru_billing_id' => ['required','string','min:3',Rule::unique('clinicians')->ignore($clinician->id)],
        ]);

        if ($validator->fails()) {
            return [
                'message' => $validator->messages()->first(),
                'status' => false,
            ];
        }
        $clinician->update(array_merge($requestedData, [
            'phone_no' => (($requestedData['phone_no'] != null && $requestedData['phone_no'] != 'null') ? $requestedData['phone_no'] : $clinician->phone),
            'email' => (($requestedData['email'] != null && $requestedData['email'] != 'null') ? $requestedData['email'] : $clinician->email),
        ]));
        $clinician->ageCovered()->delete();
        $clinician->specialties()->delete();
        $clinician->states()->delete();
        $clinician->servicesProvided()->delete();
        $clinician->locations()->delete();
        $specialties = [];
        $specialityIds = explode(',', $requestedData['specialities']);
        foreach ($specialityIds as $key => $speciality) {
            $specialties[] = [
                'clinician_id' => $clinician->id,
                'speciality_id' => $speciality,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        ClinicianSpecialty::insert($specialties);

        $ageRanges = [];
        $ageRangeIds = explode(',', $requestedData['ages_covered']);
        foreach ($ageRangeIds as $key => $ageRange) {
            $ageRanges[] = [
                'clinician_id' => $clinician->id,
                'age_range_id' => $ageRange,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        ClinicianAgeCovered::insert($ageRanges);

        $locations = [];
        $locationIds = explode(',', $requestedData['locations']);
        foreach ($locationIds as $key => $location) {
            $locationData = Location::where('id', $location)->first();
            if ($locationData) {
                $locations[] = [
                    'clinician_id' => $clinician->id,
                    'location_id' => $location,
                    'azalea_id' => $locationData->azalea_id,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ];
            }

        }
        ClinicianLocation::insert($locations);

        $states = [];
        $stateIds = explode(',', $requestedData['states']);
        foreach ($stateIds as $key => $state) {
            $states[] = [
                'clinician_id' => $clinician->id,
                'state_id' => $state,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        ClinicianState::insert($states);

        $services = [];
        $serviceIds = explode(',', $requestedData['services']);
        foreach ($serviceIds as $key => $service) {
            $services[] = [
                'clinician_id' => $clinician->id,
                'service_provided_id' => $service,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        ClinicianServiceProvided::insert($services);

        return [
            'message' => 'Updated successfully',
            'status' => true,
        ];
    }

    /**
     * Delete clinician
     *
     * @param [type] $clinician
     * @return void
     */
    public static function delete($clinician)
    {
        $clinician->delete();

        return true;
    }

    /**
     * Delete multiple clinicians
     *
     * @param [type] $ids
     * @return void
     */
    public static function deleteMultiple($ids)
    {
        Clinician::whereIn('id', $ids)->delete();

        return true;
    }

}
