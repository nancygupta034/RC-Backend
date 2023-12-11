<?php

namespace App\Http\Services\Masters;

use App\Models\AgeRange;
use Illuminate\Support\Facades\Validator;

class AgeRangeService
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function rules()
    {
        return [
            'min_age' => 'required',
            'max_age' => 'required',
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
            'min_age' => 'required|integer',
            'max_age' => 'required|integer|gt:min_age',
        ],
            [
                'max_age.gt' => 'Maximu age must be greater than minimum age',
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

        AgeRange::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getAgeRange($id)
    {
        $ageRange = AgeRange::where('id', $id)->first();

        return $ageRange;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $type
     * @return void
     */
    public static function update($requestedData, $ageRange)
    {
        $validator = Validator::make($requestedData, [
            'min_age' => 'required|integer',
            'max_age' => 'required|integer|gt:min_age',
        ]);

        if ($validator->fails()) {
            return [
                'message' => $validator->messages()->first(),
                'status' => false,
            ];
        }

        $ageRange->update($requestedData);

        return [
            'message' => "Updated successfully",
            'status' => true,
        ];
    }

    /**
     * Undocumented function
     *
     * @param [type] $speciality
     * @return void
     */
    public static function delete($ageRange)
    {
        $ageRange->delete();

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
        AgeRange::whereIn('id', $ids)->delete();

        return true;
    }

}
