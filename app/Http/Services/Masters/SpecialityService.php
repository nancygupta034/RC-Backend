<?php

namespace App\Http\Services\Masters;

use App\Models\Speciality;
use Illuminate\Support\Facades\Validator;

class SpecialityService
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function rules()
    {
        return [
            'name' => 'required|min:3',
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
            'name' => 'required|string|min:3|unique:specialities,name,NULL,NULL,deleted_at,NULL',
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
        Speciality::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getSpeciality($id)
    {
        $speciality = Speciality::where('id', $id)->first();

        return $speciality;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $speciality
     * @return void
     */
    public static function update($requestedData, $speciality)
    {
        $speciality->update($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $speciality
     * @return void
     */
    public static function delete($speciality)
    {
        $speciality->delete();

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
        Speciality::whereIn('id', $ids)->delete();

        return true;
    }

}
