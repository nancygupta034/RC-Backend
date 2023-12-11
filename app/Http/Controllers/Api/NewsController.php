<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\CMS\NewsService;
use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Get list of news
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {

            if ($request->get('active') == 'true') {

                $news = News::with('categories', 'categories.category')->where('status', 1);
                if ($request->has('category')) {
                    $cat = $request->get('category');
                    $categoryIds = Category::where('id', $cat)->orWhere('name', $cat)->pluck('id')->toArray();
                    $newsIDs = NewsCategory::whereIn('category_id', $categoryIds)->pluck('news_id')->toArray();
                    $news = $news->whereIn('id', $newsIDs);
                }
                if ($request->has('search')) {
                    $search = $request->get('search');
                    $news = $news->where('title', 'like', $search . '%')->orWhere('meta_keywords', 'like', $search . '%');
                }
                if ($request->has('take')) {
                    $news = $news->skip(0)->take($request->get('take'));
                }
                if ($request->has('recent')) {
                    $news = $news->skip(0)->take(5);
                }
                $allNewsCount = $news->get()->count();
                $news = $news->orderBy('created_at', 'desc')->get();
            } else {
                $allNewsCount = News::all()->count();
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $news = News::where('title', 'like', $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $news = News::skip($request->get('skip'))->with('categories')->take(10)->get();
                }

            }

            $data = [
                'data' => $news,
                'totalRecords' => $allNewsCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add News
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = NewsService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                if ($request->file()) {
                    $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');

                    $name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['image'] = $path;
                }
                NewsService::create($requestedData);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get News
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $news = NewsService::getNews($id);
            if ($news) {

                return Common::sendResponse(null, 200, $news, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update News
     *
     * @param Request $request
     * @param [News] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $news = NewsService::getNews($id);
            if ($news) {
                unset($news->categoryIds);
                unset($news->categoryNames);
                $validateRequest = NewsService::validateRequest($request->all(), $news);
                if ($validateRequest['status'] === true || $request->get('title') === $news['title']) {
                    $requestedData = $request->all();
                    if ($request->file()) {
                        $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                        $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
                        $path = '/storage/' . $file_path;
                        $requestedData['image'] = $path;
                    } else {
                        $requestedData['image'] = $news->image;
                    }
                    NewsService::update($requestedData, $news);

                    return Common::sendResponse('Updated successfully.', 200, $news, true);
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
            $news = NewsService::getNews($id);
            if ($news) {
                unset($news->categoryIds);
                unset($news->categoryNames);
                $validateRequest = NewsService::validateRequest($request->all(), $news);
                if ($validateRequest['status'] === true || $request->get('title') === $news['title']) {
                    $requestedData = $request->all();
                    $news->update([
                        'status' => $requestedData['status'],
                    ]);

                    return Common::sendResponse('Updated successfully.', 200, $news, true);
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
     * Delete News
     *
     * @param [News] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $news = NewsService::getNews($id);
            if ($news) {
                NewsService::delete($news);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple news
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            NewsService::deleteMultiple($request->get('news_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
