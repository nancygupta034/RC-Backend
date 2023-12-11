<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\OurStory;
use Illuminate\Http\Request;

class OurStoryController extends Controller
{
    /**
     * Get list of news
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $ourStory = OurStory::first();

            return Common::sendResponse(null, 200, $ourStory, true);
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
            $requestedData = $request->all();
            if ($request->file()) {
                $file_name = time() . '_' . $request->file('primary_image')->getClientOriginalName();
                $file_path = $request->file('primary_image')->storeAs('uploads', $file_name, 'public');
                $path = '/storage/' . $file_path;
                $requestedData['primary_image'] = $path;

                $secondary_file_name = time() . '_' . $request->file('secondary_image')->getClientOriginalName();
                $secondary_file_path = $request->file('secondary_image')->storeAs('uploads', $secondary_file_name, 'public');
                $secondary_path = '/storage/' . $secondary_file_path;
                $requestedData['secondary_image'] = $secondary_path;
            }
            OurStory::truncate();
            OurStory::create($requestedData);

            return Common::sendResponse('Added successfully.', 200, [], true);

        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

}
