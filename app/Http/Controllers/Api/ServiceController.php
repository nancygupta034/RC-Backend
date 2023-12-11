<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\CMS\Service;
use App\Models\Service as ServiceModel;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Get list of services
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $allServicesCount = ServiceModel::all()->count();

            if ($request->get('active') == 'true') {
                $services = ServiceModel::where('status', 1)->get();
            } else {
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $services = ServiceModel::where('title', 'like', $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $services = ServiceModel::skip($request->get('skip'))->take(10)->get();
                }

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
     * Add service
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = Service::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                if ($request->file()) {
                    $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');

                    $name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['image'] = $path;
                }
                Service::create($requestedData);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get service
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $service = Service::getService($id);
            if ($service) {

                return Common::sendResponse(null, 200, $service, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update service
     *
     * @param Request $request
     * @param [service] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $service = Service::getService($id);
            if ($service) {
                $validateRequest = Service::validateRequest($request->all(), $service);
                if ($validateRequest['status'] === true || $request->get('title') === $service['title']) {
                    $requestedData = $request->all();
                    if ($request->file()) {
                        $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                        $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
                        $path = '/storage/' . $file_path;
                        $requestedData['image'] = $path;
                    } else {
                        $requestedData['image'] = $service->image;
                    }
                    Service::update($requestedData, $service);

                    return Common::sendResponse('Updated successfully.', 200, $service, true);
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
     * Delete service
     *
     * @param [service] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $service = Service::getService($id);
            if ($service) {
                Service::delete($service);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple services
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            Service::deleteMultiple($request->get('service_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
