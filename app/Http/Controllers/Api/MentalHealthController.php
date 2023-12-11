<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\CMS\MentalHealthService;
use App\Models\Category;
use App\Models\MentalHealth;
use App\Models\MentalHealthCategory;
use Illuminate\Http\Request;

class MentalHealthController extends Controller
{
    /**
     * Get list of MentalHealths
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('active') == 'true') {
                $MentalHealths = MentalHealth::with('categories', 'categories.category')->where('status', 1);
                if ($request->has('category')) {
                    $cat = $request->get('category');
                    $categoryIds = Category::where('id', $cat)->orWhere('name', $cat)->pluck('id')->toArray();
                    $mentalHealthIDs = MentalHealthCategory::whereIn('category_id', $categoryIds)->pluck('mental_health_id')->toArray();
                    $MentalHealths = $MentalHealths->whereIn('id', $mentalHealthIDs);
                }
                if ($request->has('search')) {
                    $search = $request->get('search');
                    $MentalHealths = $MentalHealths->where('title', 'like', $search . '%')->orWhere('meta_keywords', 'like', $search . '%');
                }

                $allMentalHealthsCount = $MentalHealths->get()->count();

                if ($request->has('take')) {
                    $MentalHealths = $MentalHealths->skip(0)->take($request->get('take'));
                }

                if ($request->has('recent')) {
                    $MentalHealths = $MentalHealths->skip(0)->take(5);
                }
                $MentalHealths = $MentalHealths->orderBy('created_at', 'desc')->get();
            } else {
                $allMentalHealthsCount = MentalHealth::all()->count();
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $MentalHealths = MentalHealth::where('title', 'like', $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $MentalHealths = MentalHealth::skip($request->get('skip'))->with('categories')->take(10)->get();
                }
            }

            $data = [
                'data' => $MentalHealths,
                'totalRecords' => $allMentalHealthsCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add MentalHealth
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = MentalHealthService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                $images = [];
                if ($request->file() && $request->get('type') == "video") {
                    $file_name = time() . '_' . $request->file('video')->getClientOriginalName();
                    $file_path = $request->file('video')->storeAs('uploads', $file_name, 'public');

                    $name = time() . '_' . $request->file('video')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['video'] = $path;
                    $requestedData['file'] = null;
                }

                if ($request->get('type') == "image") {
                    $imageFiles = $request->file('images');
                    $requestedData['video'] = null;
                    $requestedData['file'] = null;
                    foreach ($imageFiles as $key => $imageFile) {
                        $file_name = time() . '_' . $imageFile->getClientOriginalName();
                        $file_path = $imageFile->storeAs('uploads', $file_name, 'public');
                        $name = time() . '_' . $imageFile->getClientOriginalName();
                        $path = '/storage/' . $file_path;
                        $images[] = $path;
                    }
                }

                if ($request->file() && $request->get('type') == "file") {
                    $file_name = time() . '_' . $request->file('file')->getClientOriginalName();
                    $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
                    $name = time() . '_' . $request->file('file')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['file'] = $path;
                    $requestedData['video'] = null;
                }
                MentalHealthService::create($requestedData, $images);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get MentalHealth
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $MentalHealth = MentalHealthService::getMentalHealth($id);
            if ($MentalHealth) {

                return Common::sendResponse(null, 200, $MentalHealth, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update MentalHealth
     *
     * @param Request $request
     * @param [MentalHealth] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $MentalHealth = MentalHealthService::getMentalHealth($id);
            if ($MentalHealth) {
                unset($MentalHealth->categoryIds);
                unset($MentalHealth->categoryNames);
                $validateRequest = MentalHealthService::validateRequest($request->all(), $MentalHealth);

                if ($validateRequest['status'] === true || $request->get('title') === $MentalHealth['title']) {
                    $requestedData = $request->all();
                    $images = [];
                    if ($request->get('type') == "video") {
                        if ($request->file() && $request->get('type') == "video") {
                            $file_name = time() . '_' . $request->file('video')->getClientOriginalName();
                            $file_path = $request->file('video')->storeAs('uploads', $file_name, 'public');
                            $name = time() . '_' . $request->file('video')->getClientOriginalName();
                            $path = '/storage/' . $file_path;
                            $requestedData['video'] = $path;
                            $requestedData['file'] = null;
                        }
                    } else {
                        $requestedData['video'] = $MentalHealth->video;
                    }

                    if ($request->get('type') == "image") {
                        $imageFiles = $request->file('images');
                        $imageText = $request->get('images');
                        $requestedData['video'] = null;
                        $requestedData['file'] = null;
                        if ($imageFiles) {
                            foreach ($imageFiles as $key => $imageFile) {
                                $file_name = time() . '_' . $imageFile->getClientOriginalName();
                                $file_path = $imageFile->storeAs('uploads', $file_name, 'public');
                                $name = time() . '_' . $imageFile->getClientOriginalName();
                                $path = '/storage/' . $file_path;
                                $images[] = $path;
                            }
                        }
                        if ($imageText) {
                            foreach ($request->get('images') as $key => $imageFile) {
                                $images[] = $imageFile;
                            }
                        }
                    } else {
                        $uploadedImages = $MentalHealth->images;
                        foreach ($uploadedImages as $key => $uploadedImage) {
                            $images[] = $uploadedImage->image;
                        }
                    }
                    if ($request->get('type') == "file") {
                        if ($request->file() && $request->get('type') == "file") {
                            $file_name = time() . '_' . $request->file('file')->getClientOriginalName();
                            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
                            $name = time() . '_' . $request->file('file')->getClientOriginalName();
                            $path = '/storage/' . $file_path;
                            $requestedData['file'] = $path;
                            $requestedData['video'] = null;
                        }
                    } else {
                        $requestedData['file'] = $MentalHealth->file;
                    }

                    MentalHealthService::update($requestedData, $MentalHealth, $images);

                    return Common::sendResponse('Updated successfully.', 200, $MentalHealth, true);
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
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $MentalHealth = MentalHealthService::getMentalHealth($id);
            if ($MentalHealth) {
                unset($MentalHealth->categoryIds);
                unset($MentalHealth->categoryNames);
                $validateRequest = MentalHealthService::validateRequest($request->all(), $MentalHealth);
                if ($validateRequest['status'] === true || $request->get('title') === $MentalHealth['title']) {
                    $requestedData = $request->all();
                    $MentalHealth->update([
                        'status' => $requestedData['status'],
                    ]);

                    return Common::sendResponse('Updated successfully.', 200, $MentalHealth, true);
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
     * Delete MentalHealth
     *
     * @param [MentalHealth] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $MentalHealth = MentalHealthService::getMentalHealth($id);
            if ($MentalHealth) {
                MentalHealthService::delete($MentalHealth);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple MentalHealths
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            MentalHealthService::deleteMultiple($request->get('mental_health_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
