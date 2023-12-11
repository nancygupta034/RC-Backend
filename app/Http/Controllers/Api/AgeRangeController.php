<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\Masters\AgeRangeService;
use App\Models\AgeRange;
use Illuminate\Http\Request;

class AgeRangeController extends Controller
{
    /**
     * Get list of age ranges
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $allAgeRangeCount = AgeRange::all()->count();
            if ($request->get('active') == 'true') {
                $ageRanges = AgeRange::where('status', 1)->get();
            } else {
                $ageRanges = AgeRange::skip($request->get('skip'))->take(10)->get();
            }

            $data = [
                'data' => $ageRanges,
                'totalRecords' => $allAgeRangeCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add age range
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = AgeRangeService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $minAge = $request->get('min_age');
                $maxAge = $request->get('max_age');

                $existingAgeRange = AgeRange::where('min_age', $minAge)
                    ->where('max_age', $maxAge)
                    ->first();

                if ($existingAgeRange === null) {

                    AgeRangeService::create($request->all());

                    return Common::sendResponse('Added successfully.', 200, [], true);
                } else {
                    return Common::sendResponse('The age range has already been taken');
                }
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get data of age-range
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $type = AgeRangeService::getAgeRange($id);
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
     * Update data of age-range
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $type = AgeRangeService::getAgeRange($id);
            if ($type) {

                $minAge = $request->get('min_age');
                $maxAge = $request->get('max_age');

                $existingAgeRange = AgeRange::where('min_age', $minAge)->where('max_age', $maxAge)->where('id', '!=', $id)->first();

                if ($existingAgeRange === null) {

                    $validateRequest = AgeRangeService::update($request->all(), $type);
                    if ($validateRequest['status'] === true) {

                        return Common::sendResponse('Updated successfully.', 200, $type, true);
                    } else {
                        return Common::sendResponse($validateRequest['message'], 400, [], false);
                    }
                } else {
                    return Common::sendResponse('The age range has already been taken');
                }
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete data of age-range
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $type = AgeRangeService::getAgeRange($id);
            if ($type) {
                AgeRangeService::delete($type);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple age-ranges
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            AgeRangeService::deleteMultiple($request->get('age-ranges'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
