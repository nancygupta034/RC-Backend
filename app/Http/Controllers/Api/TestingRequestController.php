<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\TestingRequestService;
use App\Models\TestingRequest;
use Illuminate\Http\Request;

class TestingRequestController extends Controller
{
    /**
     * Get list of testing-types
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('search') != "") {
                $search = $request->get('search');
                $allTypesCount = TestingRequest::where('first_name', 'like', $search . '%')->get()->count();
                $types = TestingRequest::where('first_name', 'like', $search . '%')->orWhere('email', 'like', $search . '%')->skip($request->get('skip'))->take(10)->with('testingType')->get();
            } else {
                $allTypesCount = TestingRequest::all()->count();
                $types = TestingRequest::skip($request->get('skip'))->take(10)->with('testingType')->get();
            }

            $data = [
                'data' => $types,
                'totalRecords' => $allTypesCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add testing-types
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = TestingRequestService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                TestingRequestService::create($request->all());

                return Common::sendResponse('Your request has been submitted successfully', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get testing-types
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $type = TestingRequestService::getTestingRequest($id);
            if ($type) {

                return Common::sendResponse(null, 200, $type, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
