<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get list of users
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $usersCount = User::where('email', '!=', auth()->user()->email)->get()->count();
            $users = User::where('email', '!=', auth()->user()->email)->with('role')->skip($request->get('skip'))->take(10)->get();

            $data = [
                'data' => $users,
                'totalRecords' => $usersCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add user
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = UserService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                UserService::create($request->all());

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get user data
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $user = UserService::getUser($id);
            if ($user) {

                return Common::sendResponse(null, 200, $user, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $user = UserService::getUser($id);
            if ($user) {

                $validateRequest = UserService::update($request->all(), $user);
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
     * Delete user
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $user = UserService::getUser($id);
            if ($user) {
                UserService::delete($user);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple users
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            UserService::deleteMultiple($request->get('user_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
