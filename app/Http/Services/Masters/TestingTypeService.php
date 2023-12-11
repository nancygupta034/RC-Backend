<?php

namespace App\Http\Services\Masters;

use App\Models\TestingType;
use Illuminate\Support\Facades\Validator;

class TestingTypeService
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
            'name' => 'required|string|min:3|unique:testing_types,name,NULL,NULL,deleted_at,NULL',
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
        TestingType::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getTestingType($id)
    {
        $type = TestingType::where('id', $id)->first();

        return $type;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $type
     * @return void
     */
    public static function update($requestedData, $type)
    {
        $type->update($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $speciality
     * @return void
     */
    public static function delete($type)
    {
        $type->delete();

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
        TestingType::whereIn('id', $ids)->delete();

        return true;
    }

}
