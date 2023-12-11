<?php

namespace App\Http\Controllers\Api\Azalea;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlotController extends Controller
{

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function getSlots(Request $request)
    {
        try {
            $accessToken = Common::getAzaleaAccessToken();
            $date = $request->has('date') ? $request->get('date') : date("Y-m-d");
            $locationId = $request->get('location');
            $clinician = $request->get('clinician');
            $nearest_slots = $request->get('nearest_slots');
            $only_slots = $request->get('only_slots');
            $slots = [];
            if ($nearest_slots) {
                $slots = $this->getNearestSlots($accessToken->access_token, $date, $locationId, $clinician);
            } else {
                $url = "https://app.azaleahealth.com/fhir/R4/142114/Slot?start=$date&schedule.actor=Practitioner/$clinician&schedule.actor=Location/$locationId&status=free&_count=7";
                Log::info($url);
                $curlRequest = Common::handleCurlGetRequest($url, $accessToken->access_token);
                $decodedData = json_decode($curlRequest['response'], true);
                if (isset($decodedData['error'])) {
                    $slots = $slots;
                } else {
                    if ($decodedData && sizeof($decodedData) && isset($decodedData['entry'])) {
                        foreach ($decodedData['entry'] as $decodedRecord) {
                            $resource = $decodedRecord['resource'];
                            if ($only_slots) {
                                $slots[] = [
                                    "date" => date("D M d, h:ia", strtotime($resource['start'])),
                                    "start" => $resource['start'],
                                    "time" => date("h:i a", strtotime($resource['start'])),
                                    "end" => $resource['end'],
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
                            } else {
                                $slots[date("d M - D", strtotime($resource['start']))][] = [
                                    "date" => date("D M d, h:ia", strtotime($resource['start'])),
                                    "start" => $resource['start'],
                                    "time" => date("h:i a", strtotime($resource['start'])),
                                    "end" => $resource['end'],
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
                            }
                        }
                    }
                }
            }

            return Common::sendResponse(null, 200, $slots, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getNearestSlots($access_token, $currentDate, $locationId, $azaleaId)
    {
        $maxDays = 7;
        $slots = [];
        $currentDate = date("Y-m-d", strtotime("$currentDate -1 day"));
        $slotDates = [];
        for ($i = 0; $i < 7; $i++) {
            $dateNeedToFind = $i + 1;
            $nextDate = date("Y-m-d", strtotime("$currentDate +$dateNeedToFind day"));
            if ($i > 0) {
                $slotDates[] = $nextDate;
            }

            $url = "https://app.azaleahealth.com/fhir/R4/142114/Slot?start=$nextDate&schedule.actor=Practitioner/$azaleaId&schedule.actor=Location/$locationId&status=free&_count=7";
            Log::info($url);
            $curlRequest = Common::handleCurlGetRequest($url, $access_token);

            $decodedData = json_decode($curlRequest['response'], true);

            if (isset($decodedData['error'])) {
                dd("error => ", $decodedData['error']);
            } else {
                if ($decodedData && sizeof($decodedData) && isset($decodedData['entry'])) {
                    foreach ($decodedData['entry'] as $decodedRecord) {
                        $resource = $decodedRecord['resource'];
                        if (sizeof($slots) < 10) {
                            $slots[date("d M - D", strtotime($resource['start']))][] = [
                                "date" => date("D M d, h:ia", strtotime($resource['start'])),
                                "start" => $resource['start'],
                                "id" => $resource['id'],
                                "status" => $resource['status'],
                                "url" => $url,
                                "time" => date("h:i a", strtotime($resource['start'])),
                                "appointmentType" => isset($resource['appointmentType']) ? $resource['appointmentType'] : [],
                                "formatted_date" => date("Y-m-d", strtotime($resource['start'])),
                                "start_time" => date("h:i", strtotime($resource['start'])),
                                "end_time" => date("h:i", strtotime($resource['end'])),
                                "start_date_time" => $resource['start'],
                                "end_date_time" => $resource['end'],
                            ];
                        }
                    }
                }
            }
        }
        $data = [
            'slots' => (array) $slots,
            'dates' => $slotDates,
        ];

        return $data;
    }

}
