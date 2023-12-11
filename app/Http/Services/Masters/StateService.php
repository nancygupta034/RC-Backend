<?php

namespace App\Http\Services\Masters;

use App\Models\StateCovered;
use Illuminate\Support\Facades\Validator;

class StateService
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function rules()
    {
        return [
            'state_id' => 'required',
        ];
    }

    /**
     * Undocumented function
     *
     * @param [array] $requestedData
     * @return void
     */
    public static function validateRequest($requestedData)
    {
        $validator = Validator::make($requestedData, [
            'state_id' => 'required',
        ]);

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
        StateCovered::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getStateCovered($id)
    {
        $stateCovered = StateCovered::with('state')->find($id);
        // $stateCovered = StateCovered::where('id', $id)->first();

        return $stateCovered;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $stateCovered
     * @return void
     */
    public static function update($requestedData, $stateCovered)
    {
        $stateCovered->update($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $speciality
     * @return void
     */
    public static function delete($stateCovered)
    {
        $stateCovered->delete();

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
        StateCovered::whereIn('id', $ids)->delete();

        return true;
    }

}
