<?php

namespace App\Http\Services\CMS;

use App\Models\Service as ServiceModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Service
{

    /**
     * Undocumented function
     *
     * @param [array] $requestedData
     * @return void
     */
    public static function validateRequest($requestedData)
    {
        $validator = Validator::make($requestedData, [
            'title' => 'required|string|min:3|unique:services,title,NULL,NULL,deleted_at,NULL|max:100',
            'sub_title' => 'required|string|min:3|max:200',
            'description' => 'required|',
            'meta_title' => 'required|',
            'meta_description' => 'required|',
            'meta_keywords' => 'required|',
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
        $service = ServiceModel::create($requestedData);
        $slug = Str::slug($requestedData['title']);
        $slugAlreadyExists = ServiceModel::where('slug', $slug)->first();
        if ($slugAlreadyExists) {
            $slug = $slug . '-' . $service->id;
        }
        $service->update([
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
    public static function getService($value)
    {
        $service = ServiceModel::where('id', $value)->orWhere('slug', $value)->first();

        return $service;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $service
     * @return void
     */
    public static function update($requestedData, $service)
    {
        $service->update($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $service
     * @return void
     */
    public static function delete($service)
    {
        $service->delete();

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
        ServiceModel::whereIn('id', $ids)->delete();

        return true;
    }

}
