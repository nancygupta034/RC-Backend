<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $allStatesCount = State::all()->count();

            if ($request->get('active') == 'true') {
                $states = State::where('status', 1)->get();

                return Common::sendResponse(null, 200, $states, true);
            } else {
                $states = State::skip($request->get('skip'))->take(10)->get();

                $data = [
                    'data' => $states,
                    'totalRecords' => $allStatesCount,
                ];

                return Common::sendResponse(null, 200, $data, true);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $speciality = State::where('id', $id)->first();
            if ($speciality) {
                $speciality->update($request->all());

                return Common::sendResponse('Updated successfully.', 200, $speciality, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
