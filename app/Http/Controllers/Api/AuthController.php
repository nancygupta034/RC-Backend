<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\AuthService;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        try {
            
            // $validateRequest = AuthService::validateRequest($request->all());
            // dd($validateRequest);
            // if ($validateRequest['status'] === true) {
                // dd("gdfgdf");
                if (!Auth::attempt($request->only(['email', 'password']))) {
                   
                    return Common::sendResponse('Email & Password does not match with our record.', 401, [], false);
                } else {
                    
                    $user = User::where('email', $request->email)->with('role')->first();
                    $user->token = $user->createToken("API TOKEN")->plainTextToken;
                    return Common::sendResponse(null, 200, $user, true);
                }
            // } else {

            //     return Common::sendResponse($validateRequest['message'], 400, [], false);
            // }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get auth user data
     *
     * @param Request $request
     * @return void
     */
    public function authData(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user) {
                $userRole = UserRole::where('id', $user->role_id)->first();
                $user->roleData = $userRole;
                return Common::sendResponse(null, 200, $user, true);
            } else {

                return Common::sendResponse('Unauthorized', 401, [], true);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

            return Common::sendResponse('Logout successfully', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    public function azaleaResponse(Request $request)
    {
        try {

            return Common::sendResponse('Success', 200, $request->all(), true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    public function updatePassword(Request $request)
    {
        #Match The Old Password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return Common::sendResponse("Current password doesn't match!", 200, [], false);
        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return Common::sendResponse("Password updated successfully.", 200, [], true);
    }
}
