<?php

namespace App\Http\Services;

use App\Models\Team;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamService
{
    /**
     * Undocumented function
     *
     * @param [array] $requestedData
     * @return void
     */
    public static function validateRequest($requestedData, $team)
    {
        if ($team && $team->id) {
            $validator = Validator::make($requestedData, [
                'name' => 'required|string|min:3',
                'email' => ['required', 'email', Rule::unique('teams')->ignore($team->id)],
                'description' => 'required|string|min:50',
            ]);
        } else {
            $validator = Validator::make($requestedData, [
                'name' => 'required|string|min:3',
                'email' => 'required|email|unique:teams,email,NULL,NULL,deleted_at,NULL',
                'description' => 'required|string|min:50',
            ]);
        }

        return [
            'message' => $validator->fails() ? $validator->messages()->first() : null,
            'status' => $validator->fails() ? false : true,
        ];
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @return void
     */
    public static function create($requestedData)
    {
        $team = Team::create($requestedData);
        $slug = Str::slug($requestedData['name']);
        $slugAlreadyExists = Team::where('slug', $slug)->first();
        if ($slugAlreadyExists) {
            $slug = $slug . '-' . $team->id;
        }
        $team->update([
            'slug' => $slug,
        ]);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public static function getTeam($value)
    {
        $team = Team::where('id', $value)->orWhere('slug', $value)->first();

        return $team;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $team
     * @return void
     */
    public static function update($requestedData, $team)
    {
        $team->update($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $team
     * @return void
     */
    public static function delete($team)
    {
        $team->delete();

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $ids
     * @return void
     */
    public static function deleteMultiple($ids)
    {
        Team::whereIn('id', $ids)->delete();

        return true;
    }

}
