<?php

namespace App\Http\Services\CMS;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsService
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
            'title' => 'required|string|min:3|unique:news,title,NULL,NULL,deleted_at,NULL',
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
        $news = News::create($requestedData);
        $slug = Str::slug($requestedData['title']);
        $slugAlreadyExists = News::where('slug', $slug)->first();
        if ($slugAlreadyExists) {
            $slug = $slug . '-' . $news->id;
        }
        $news->update([
            'slug' => $slug,
        ]);

        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'news_id' => $news->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        NewsCategory::insert($categories);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public static function getNews($value)
    {
        $news = News::where('id', $value)->orWhere('slug', $value)->with('categories', 'categories.category')->first();
        if ($news) {
            $categoryIds = [];
            $categoryNames = [];
            foreach ($news->categories as $key => $category) {
                $categoryIds[] = $category->category_id;
                $categoryNames[] = $category->category ? $category->category->name : "";
            }
            $news->categoryIds = $categoryIds;
            $news->categoryNames = implode(", ", $categoryNames);
        }

        return $news;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $news
     * @return void
     */
    public static function update($requestedData, $news)
    {
        $news->update($requestedData);
        $news->categories()->delete();
        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'news_id' => $news->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        NewsCategory::insert($categories);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $news
     * @return void
     */
    public static function delete($news)
    {
        $news->categories()->delete();
        $news->delete();

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
        NewsCategory::whereIn('news_id', $ids)->delete();
        News::whereIn('id', $ids)->delete();

        return true;
    }

}
