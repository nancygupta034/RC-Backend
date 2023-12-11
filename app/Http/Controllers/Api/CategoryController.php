<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\Masters\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $allCategoriesCount = Category::where('type', $request->get('type'))->get()->count();

            if ($request->get('active') == 'true') {
                $categories = Category::where('type', $request->get('type'))->where('status', 1)->get();
            } else {
                $categories = Category::where('type', $request->get('type'))->skip($request->get('skip'))->take(10)->get();
            }
            $data = [
                'data' => $categories,
                'totalRecords' => $allCategoriesCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = CategoryService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $alreadyExists = Category::where('name', $request->get('name'))->where('type', $request->get('type'))->first();
                if ($alreadyExists) {

                    return Common::sendResponse('Name is already taken', 400, [], false);
                }

                CategoryService::create($request->all());

                return Common::sendResponse('Category created successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $category = CategoryService::getCategory($id);
            if ($category) {

                return Common::sendResponse(null, 200, $category, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $category = CategoryService::getCategory($id);
            if ($category) {
                $validateRequest = CategoryService::validateRequest($request->all(), $category);
                if ($validateRequest['status'] === true || $request->get('name') === $category['name']) {
                    CategoryService::update($request->all(), $category);

                    return Common::sendResponse('Updated successfully.', 200, $category, true);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = CategoryService::getCategory($id);
            if ($category) {
                CategoryService::delete($category);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multipla categories
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            CategoryService::deleteMultiple($request->get('categories'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
