<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\Masters\StateService;
use App\Models\StateCovered;
use Illuminate\Http\Request;

class StateCoveredController extends Controller
{
    /**
     * Get list of states covered
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $allState = StateCovered::all()->count();
            $states = StateCovered::with('state')->get()->skip($request->get('skip'))->take(10);

            $data = [
                'data' => $states,
                'totalRecords' => $allState,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add states covered
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = StateService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                StateService::create($request->all());

                return Common::sendResponse('State created successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update states covered
     *
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        try {
            $stateCovered = StateService::getStateCovered($id);
            if ($stateCovered) {

                return Common::sendResponse(null, 200, $stateCovered, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update states covered
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        try {
            $updateStateCovered = StateService::getStateCovered($id);
            if ($updateStateCovered) {
                StateService::update($request->all(), $updateStateCovered);

                return Common::sendResponse('Updated successfully.', 200, $updateStateCovered, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete states covered
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $deleteStateCovered = StateService::getStateCovered($id);
            if ($deleteStateCovered) {
                StateService::delete($deleteStateCovered);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple states covered
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            StateService::deleteMultiple($request->get('state_covered_id'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
