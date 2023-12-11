<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Get list of user roles
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $roles = UserRole::all();

            return Common::sendResponse(null, 200, $roles, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
