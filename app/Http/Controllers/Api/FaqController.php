<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\CMS\FaqService;
use App\Models\Category;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Get list of Faq
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {

            if ($request->get('active') == 'true') {

                $faq = Faq::with('categories', 'categories.category')->where('status', 1);
                if ($request->has('category')) {
                    $cat = $request->get('category');
                    $categoryIds = Category::where('id', $cat)->orWhere('name', $cat)->pluck('id')->toArray();
                    $faqIDs = FaqCategory::whereIn('category_id', $categoryIds)->pluck('faq_id')->toArray();
                    $faq = $faq->whereIn('id', $faqIDs);
                }
                if ($request->has('search')) {
                    $search = $request->get('search');
                    $faq = $faq->where('question', 'like', '%' . $search . '%')->orWhere('meta_keywords', 'like', $search . '%');
                }
                if ($request->has('take')) {
                    $faq = $faq->skip(0)->take($request->get('take'));
                }
                if ($request->has('recent')) {
                    $faq = $faq->skip(0)->take(5);
                }
                $allFaqCount = $faq->get()->count();
                $faq = $faq->orderBy('created_at', 'desc')->get();
            } else {
                $allFaqCount = Faq::all()->count();
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $faq = Faq::where('question', 'like', '%' . $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $faq = Faq::skip($request->get('skip'))->with('categories')->take(10)->get();
                }

            }

            $data = [
                'data' => $faq,
                'totalRecords' => $allFaqCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add FAQ
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = FaqService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                FaqService::create($requestedData);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get Faq
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $faq = FaqService::getFaq($id);
            if ($faq) {

                return Common::sendResponse(null, 200, $faq, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update Faq
     *
     * @param Request $request
     * @param [Faq] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $faq = FaqService::getFaq($id);
            if ($faq) {
                unset($faq->categoryIds);
                unset($faq->categoryNames);
                $validateRequest = FaqService::validateRequest($request->all(), $faq);
                if ($validateRequest['status'] === true || $request->get('question') === $faq['question']) {
                    $requestedData = $request->all();
                    FaqService::update($requestedData, $faq);

                    return Common::sendResponse('Updated successfully.', 200, $faq, true);
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
            $faq = FaqService::getFaq($id);
            if ($faq) {
                unset($faq->categoryIds);
                unset($faq->categoryNames);
                $validateRequest = FaqService::validateRequest($request->all(), $faq);
                if ($validateRequest['status'] === true || $request->get('question') === $faq['question']) {
                    $requestedData = $request->all();
                    $faq->update([
                        'status' => $requestedData['status'],
                    ]);

                    return Common::sendResponse('Updated successfully.', 200, $faq, true);
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
     * Delete Faq
     *
     * @param [Faq] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $faq = FaqService::getFaq($id);
            if ($faq) {
                FaqService::delete($faq);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple Faq
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            FaqService::deleteMultiple($request->get('faq_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
