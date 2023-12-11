<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Services\TeamService;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Get list of Teams
     *
     * @return void
     */
    public function index(Request $request)
    {
        try {
            $allTeamsCount = Team::all()->count();

            if ($request->get('active') == 'true') {

                $teams = Team::where('status', 1);

                if ($request->has('take')) {
                    $teams = $teams->skip(0)->take($request->get('take'));
                }
                $teams = $teams->get();
            } else {
                if ($request->get('search') != "") {
                    $search = $request->get('search');
                    $teams = Team::where('name', 'like', $search . '%')->skip($request->get('skip'))->take(10)->get();
                } else {
                    $teams = Team::skip($request->get('skip'))->take(10)->get();
                }

            }

            $data = [
                'data' => $teams,
                'totalRecords' => $allTeamsCount,
            ];

            return Common::sendResponse(null, 200, $data, true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Add Team
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = TeamService::validateRequest($request->all(), null);
            if ($validateRequest['status'] === true) {
                $requestedData = $request->all();
                if ($request->file()) {
                    $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');

                    $name = time() . '_' . $request->file('image')->getClientOriginalName();
                    $path = '/storage/' . $file_path;
                    $requestedData['image'] = $path;
                }
                TeamService::create($requestedData);

                return Common::sendResponse('Added successfully.', 200, [], true);
            } else {
                return Common::sendResponse($validateRequest['message'], 400, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Get Team
     *
     * @param Request $request
     * @param [number] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        try {
            $team = TeamService::getTeam($id);
            if ($team) {

                return Common::sendResponse(null, 200, $team, true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Update Team
     *
     * @param Request $request
     * @param [Team] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            $team = TeamService::getTeam($id);
            if ($team) {
                $validateRequest = TeamService::validateRequest($request->all(), $team);
                if ($validateRequest['status'] === true || $request->get('title') === $team['title']) {
                    $requestedData = $request->all();
                    if ($request->file()) {
                        $file_name = time() . '_' . $request->file('image')->getClientOriginalName();
                        $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
                        $path = '/storage/' . $file_path;
                        $requestedData['image'] = $path;
                    } else {
                        $requestedData['image'] = $team->image;
                    }
                    TeamService::update($requestedData, $team);

                    return Common::sendResponse('Updated successfully.', 200, $team, true);
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
            $team = TeamService::getTeam($id);
            if ($team) {
                $validateRequest = TeamService::validateRequest($request->all(), $team);
                if ($validateRequest['status'] === true || $request->get('title') === $team['title']) {
                    $requestedData = $request->all();
                    $team->update([
                        'status' => $requestedData['status'],
                    ]);

                    return Common::sendResponse('Updated successfully.', 200, $team, true);
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
     * Delete Team
     *
     * @param [Team] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $team = TeamService::getTeam($id);
            if ($team) {
                TeamService::delete($team);

                return Common::sendResponse('Deleted successfully.', 200, [], true);
            } else {
                return Common::sendResponse('No record found', 200, [], false);
            }
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }

    /**
     * Delete multiple Teams
     *
     * @param Request $request
     * @return void
     */
    public function deleteMultipleRecord(Request $request)
    {
        try {
            TeamService::deleteMultiple($request->get('team_ids'));

            return Common::sendResponse('Deleted successfully.', 200, [], true);
        } catch (\Exception $e) {

            return Common::sendResponse($e->getMessage(), 500, [], false);
        }
    }
}
