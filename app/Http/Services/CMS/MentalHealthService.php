<?php

namespace App\Http\Services\CMS;

use App\Models\MentalHealth;
use App\Models\MentalHealthCategory;
use App\Models\MentalHealthImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MentalHealthService
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
            'title' => 'required|string|min:3',
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
    public static function create($requestedData, $images)
    {
        $mentalHealth = MentalHealth::create($requestedData);
        $slug = Str::slug($requestedData['title']);
        $slugAlreadyExists = MentalHealth::where('slug', $slug)->first();
        if ($slugAlreadyExists) {
            $slug = $slug . '-' . $mentalHealth->id;
        }
        $mentalHealth->update([
            'slug' => $slug,
        ]);

        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'mental_health_id' => $mentalHealth->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        MentalHealthCategory::insert($categories);
        $imagesArr = [];
        foreach ($images as $key => $image) {
            $imagesArr[] = [
                'mental_health_id' => $mentalHealth->id,
                'image' => $image,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        MentalHealthImage::insert($imagesArr);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public static function getMentalHealth($value)
    {
        $mentalHealth = MentalHealth::where('id', $value)->orWhere('slug', $value)->with('images', 'categories', 'categories.category')->first();
        if ($mentalHealth) {
            $categoryIds = [];
            $categoryNames = [];
            foreach ($mentalHealth->categories as $key => $category) {
                $categoryIds[] = $category->category_id;
                $categoryNames[] = $category->category ? $category->category->name : "";
            }
            $mentalHealth->categoryIds = $categoryIds;
            $mentalHealth->categoryNames = implode(", ", $categoryNames);
        }

        return $mentalHealth;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $mentalHealth
     * @return void
     */
    public static function update($requestedData, $mentalHealth, $images)
    {
        $mentalHealth->update($requestedData);
        $mentalHealth->categories()->delete();
        $mentalHealth->images()->delete();
        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'mental_health_id' => $mentalHealth->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        MentalHealthCategory::insert($categories);

        $imagesArr = [];
        foreach ($images as $key => $image) {
            $imagesArr[] = [
                'mental_health_id' => $mentalHealth->id,
                'image' => $image,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        MentalHealthImage::insert($imagesArr);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $mentalHealth
     * @return void
     */
    public static function delete($mentalHealth)
    {
        $mentalHealth->categories()->delete();
        $mentalHealth->delete();

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
        MentalHealthCategory::whereIn('mental_health_id', $ids)->delete();
        MentalHealth::whereIn('id', $ids)->delete();

        return true;
    }

}
