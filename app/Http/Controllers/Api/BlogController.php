<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\BlogService;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get list of blogs
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('active') == 'true') {

                $blogs = Blog::with('categories', 'categories.category')->where('status', 1);
                if ($request->has('category')) {
                    $cat = $request->get('category');
                    $categoryIds = Category::where('id', $cat)->orWhere('name', $cat)->pluck('id')->toArray();
                    $blogIDs = BlogCategory::whereIn('category_id', $categoryIds)->pluck('blog_id')->toArray();
                    $blogs = $blogs->whereIn('id', $blogIDs);
                }
                if ($request->has('search')) {
                    $search = $request->get('search');
                    $blogs = $blogs->where('title', 'like', $search . '%')->orWhere('meta_keywords', 'like', $search . '%');
                }
                if ($request->has('take')) {
                    $blogs = $blogs->skip(0)->take($request->get('take'));
                }
                if ($request->has('recent')) {
                    $blogs = $blogs->skip(0)->take(5);
                }
                $allBlogsCount = $blogs->get()->count();
                $blogs = $blogs->orderBy('created_at', 'desc')->get();
            } else {
                $allBlogsCount = Blog::all()->count();
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $blogs = Blog::where('title', 'like', $search . '%')->orWhere('author_name', 'like', $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $blogs = Blog::skip($request->get('skip'))->with('categories')->take(10)->get();
                }
            }

            $data = [
                'data' => $blogs,
                'totalRecords' => $allBlogsCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add blog
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = BlogService::validateRequest($request->all());
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                if ($request->file()) {
                    $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');

                    $name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['image'] = $path;
                }
                BlogService::create($requestedData);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get blog
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $blog = BlogService::getBlog($id);
            if ($blog) {

                return Common::sendResponse(null, 200, $blog, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update blog
     *
     * @param Request $request
     * @param [blog] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $blog = BlogService::getBlog($id);
            if ($blog) {
                unset($blog->categoryIds);
                unset($blog->categoryNames);
                $validateRequest = BlogService::validateRequest($request->all(), $blog);
                if ($validateRequest['status'] === true || $request->get('title') === $blog['title']) {
                    $requestedData = $request->all();
                    if ($request->file()) {
                        $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                        $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
                        $path = '/storage/' . $file_path;
                        $requestedData['image'] = $path;
                    } else {
                        $requestedData['image'] = $blog->image;
                    }
                    BlogService::update($requestedData, $blog);

                    return Common::sendResponse('Updated successfully.', 200, $blog, true);
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
            $blog = BlogService::getBlog($id);
            if ($blog) {
                unset($blog->categoryIds);
                unset($blog->categoryNames);
                $validateRequest = BlogService::validateRequest($request->all(), $blog);
                if ($validateRequest['status'] === true || $request->get('title') === $blog['title']) {
                    $requestedData = $request->all();
                    $blog->update([
                        'status' => $requestedData['status'],
                    ]);

                    return Common::sendResponse('Updated successfully.', 200, $blog, true);
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
     * Delete blog
     *
     * @param [blog] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $blog = BlogService::getBlog($id);
            if ($blog) {
                BlogService::delete($blog);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple blogs
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            BlogService::deleteMultiple($request->get('blog_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
