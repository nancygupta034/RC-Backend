<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\Masters\TestingTypeService;
use App\Models\TestingType;
use Illuminate\Http\Request;

class TestingTypeController extends Controller
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
            $allTypesCount = TestingType::all()->count();
            $types = TestingType::skip($request->get('skip'))->take(10)->get();

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
            $validateRequest = TestingTypeService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                TestingTypeService::create($request->all());

                return Common::sendResponse('Added successfully.', 200, [], true);
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
            $type = TestingTypeService::getTestingType($id);
            if ($type) {

                return Common::sendResponse(null, 200, $type, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update testing-types
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $type = TestingTypeService::getTestingType($id);
            if ($type) {
                $validateRequest = TestingTypeService::validateRequest($request->all(), $type);
                if ($validateRequest['status'] === true || $request->get('name') === $type['name']) {
                    TestingTypeService::update($request->all(), $type);

                    return Common::sendResponse('Updated successfully.', 200, $type, true);
                } else {
                    return Common::sendResponse('The name has already been taken', 400, [], false);
                }

            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete testing-types
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $type = TestingTypeService::getTestingType($id);
            if ($type) {
                TestingTypeService::delete($type);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple testing-types
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            TestingTypeService::deleteMultiple($request->get('types'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
