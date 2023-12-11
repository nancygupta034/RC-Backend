<?php

namespace App\Http\Services\CMS;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FaqService
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
            'question' => 'required|string|min:20|unique:faqs',
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
        $faq = Faq::create($requestedData);
        $slug = Str::slug($requestedData['question']);
        $slugAlreadyExists = Faq::where('slug', $slug)->first();
        if ($slugAlreadyExists) {
            $slug = $slug . '-' . $faq->id;
        }
        $faq->update([
            'slug' => $slug,
        ]);

        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'faq_id' => $faq->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        FaqCategory::insert($categories);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public static function getFaq($value)
    {
        $faq = Faq::where('id', $value)->orWhere('slug', $value)->with('categories', 'categories.category')->first();
        if ($faq) {
            $categoryIds = [];
            $categoryNames = [];
            foreach ($faq->categories as $key => $category) {
                $categoryIds[] = $category->category_id;
                $categoryNames[] = $category->category ? $category->category->name : "";
            }
            $faq->categoryIds = $categoryIds;
            $faq->categoryNames = implode(", ", $categoryNames);
        }

        return $faq;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $faq
     * @return void
     */
    public static function update($requestedData, $faq)
    {
        $faq->update($requestedData);
        $faq->categories()->delete();
        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'faq_id' => $faq->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        FaqCategory::insert($categories);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $faq
     * @return void
     */
    public static function delete($faq)
    {
        $faq->categories()->delete();
        $faq->delete();

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
        FaqCategory::whereIn('faq_id', $ids)->delete();
        Faq::whereIn('id', $ids)->delete();

        return true;
    }

}
