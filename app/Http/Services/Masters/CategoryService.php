<?php

namespace App\Http\Services\Masters;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryService
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
            'name' => 'required|string|min:3',
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
        Category::create($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function getCategory($id)
    {
        $category = Category::where('id', $id)->first();

        return $category;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $category
     * @return void
     */
    public static function update($requestedData, $category)
    {
        $category->update($requestedData);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $category
     * @return void
     */
    public static function delete($category)
    {
        $category->delete();

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
        Category::whereIn('id', $ids)->delete();

        return true;
    }

}
