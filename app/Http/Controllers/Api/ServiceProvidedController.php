<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\Masters\ServiceProvidedService;
use App\Models\ClinicianServiceProvided;
use App\Models\ServiceProvided;
use Illuminate\Http\Request;

class ServiceProvidedController extends Controller
{
    /**
     * Get list of service-provider
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $allServicesCount = ServiceProvided::all()->count();

            if ($request->get('active') == 'true') {
                $services = ServiceProvided::where('status', 1)->get();
            } elseif ($request->get('clinician_id')) {
                $serviceIDs = ClinicianServiceProvided::where('clinician_id', $request->get('clinician_id'))->pluck('service_provided_id')->toArray();
                $services = ServiceProvided::whereIn('id', $serviceIDs)->where('status', 1)->get();
            } else {
                $services = ServiceProvided::skip($request->get('skip'))->take(10)->get();
            }

            $data = [
                'data' => $services,
                'totalRecords' => $allServicesCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add service-provider
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = ServiceProvidedService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                ServiceProvidedService::create($request->all());

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get data of service-provider
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $type = ServiceProvidedService::getServiceProvided($id);
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
     * Update service-provider
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $type = ServiceProvidedService::getServiceProvided($id);
            if ($type) {
                $validateRequest = ServiceProvidedService::validateRequest($request->all(), $type);
                if ($validateRequest['status'] === true || $request->get('name') === $type['name']) {
                    ServiceProvidedService::update($request->all(), $type);

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
     * Delete service-provider
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $type = ServiceProvidedService::getServiceProvided($id);
            if ($type) {
                ServiceProvidedService::delete($type);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple service-providers
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            ServiceProvidedService::deleteMultiple($request->get('services-provided'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
