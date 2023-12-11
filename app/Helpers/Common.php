<?php

namespace App\Helpers;

class Common
{

    /**
     * This fn will use to use to send response in json encoded format
     *
     * @param [type] $msg
     * @param integer $statusCd
     * @param array $data
     * @param boolean $success
     * @return void
     */
    public static function sendResponse($msg = null, $statusCd = 200, $data = [], $success = false)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $msg,
        ], $statusCd);
    }

    /**
     * Handle Curl get request
     *
     * @param [type] $url
     * @param [type] $token
     * @return void
     */
    public static function handleCurlGetRequest($url, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, // your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return [
            'response' => $response,
            'err' => $err,
        ];
    }

    /**
     * Handle Curl post request
     *
     * @param [type] $url
     * @param [type] $token
     * @param [type] $payload
     * @return void
     */
    public static function handleCurlPostRequest($url, $token, $payload, $method = "POST")
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, // your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                // 'Content-Type: multipart/form-data',
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Prefer: return=representation',
            ),
            CURLOPT_POSTFIELDS => json_encode($payload),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return [
            'response' => $response,
            'err' => $err,
        ];
    }

    /**
     * This fn is used to get access token from azalea
     *
     * @return void
     */
    public static function getAzaleaAccessToken()
    {
        $url = 'https://app.azaleahealth.com/fhir/R4/sandbox/oauth/token';

        $postRequest = array(
            'client_id' => '194',
            'client_secret' => '9634d086b5d48e2887173ab18f8c3e96',
            'grant_type' => 'client_credentials',
            'scope' => 'system/Practitioner.read system/Schedule.read system/Slot.read system/Appointment.* system/Patient.* system/Device.read system/Immunization.read system/Location.read',
        );
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, // your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: multipart/form-data',
                'Prefer: return=representation',
            ),
            CURLOPT_POSTFIELDS => $postRequest,
        ));

        $response = curl_exec($curl);
        $jsonData = json_decode($response);
        curl_close($curl);

        return $jsonData;
    }

    /**
     * Undocumented function
     *
     * @param [type] $str
     * @param [type] $start
     * @param [type] $end
     * @return void
     */
    public static function getStringBetween($str, $start, $end)
    {
        $startPos = strpos($str, $start);
        $endPos = strpos($str, $end);

        if ($startPos === false || $endPos === false) {
            return false; // Start or end character not found in the string
        }

        $startPos += strlen($start);
        $length = $endPos - $startPos;

        return substr($str, $startPos, $length);
    }
}
