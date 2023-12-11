<?php

namespace App\Http\Controllers\Api\Azalea;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function getAccessToken(Request $request)
    {
        try {

            $token = Common::getAzaleaAccessToken();

            return Common::sendResponse(null, 200, $token, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
