<?php

namespace App\Console\Commands;

use App\Helpers\Common;
use App\Models\ClinicianInsurance;
use Illuminate\Console\Command;

class UpdateInsurance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:insurance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://credentialing.simplibill.io/api/login';
        $postRequest = array(
            'email' => 'api.rcl@simplibill.io',
            'password' => '@p!@2023!',
        );
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, // your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(),
            CURLOPT_POSTFIELDS => $postRequest,
        ));
        $response = curl_exec($curl);
        $jsonData = json_decode($response);
        curl_close($curl);

        if ($jsonData && $jsonData->code == 200) {
            $token = $jsonData->payload[0]->token;
            $url = 'https://credentialing.simplibill.io/api/provider';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url, // your preferred link
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                    'X-token: ' . $token,
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $jsonData = json_decode($response);
            curl_close($curl);
            $insurancePlansArray = [];
            if ($jsonData && $jsonData->code == 200) {
                $data = (array) $jsonData->payload;
                foreach ($data as $key => $provider) {
                    // if ($provider->id == 225) {
                        if ($provider->plan == null) {
                            $insurancePlansArray[] = [
                                'clinician_id' => $provider->id,
                                'insurance_id' => $provider->insurance_id,
                                'plan_id' => null,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            ];
                        } else {
                            $plans = explode(',', $provider->plan);
                            foreach ($plans as $pKey => $plan) {
                                $string = $plan;
                                $first = "[";
                                $second = "]";
                                $result = Common::getStringBetween($string, $first, $second);
                                if ($result !== false) {
                                    $insurancePlansArray[] = [
                                        'clinician_id' => $provider->id,
                                        'insurance_id' => $provider->insurance_id,
                                        'plan_id' => $result,
                                        'created_at' => date('Y-m-d h:i:s'),
                                        'updated_at' => date('Y-m-d h:i:s'),
                                    ];
                                }
                            }
                        }
                    // }
                }
                ClinicianInsurance::truncate();
                ClinicianInsurance::insert($insurancePlansArray);
            }
        }

        return true;
    }
}
