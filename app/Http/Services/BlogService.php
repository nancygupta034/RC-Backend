<?php

namespace App\Http\Services;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogService
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
            'title' => 'required|string|min:3|unique:blogs,title,NULL,NULL,deleted_at,NULL',
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
        $blog = Blog::create($requestedData);
        $slug = Str::slug($requestedData['title']);
        $slugAlreadyExists = Blog::where('slug', $slug)->first();
        if ($slugAlreadyExists) {
            $slug = $slug . '-' . $blog->id;
        }
        $blog->update([
            'slug' => $slug,
        ]);

        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'blog_id' => $blog->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        BlogCategory::insert($categories);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public static function getBlog($value)
    {
        $blog = Blog::where('id', $value)->orWhere('slug', $value)->with('categories', 'categories.category')->first();
        if ($blog) {
            $categoryIds = [];
            $categoryNames = [];
            foreach ($blog->categories as $key => $category) {
                $categoryIds[] = $category->category_id;
                $categoryNames[] = $category->category ? $category->category->name : "";
            }
            $blog->categoryIds = $categoryIds;
            $blog->categoryNames = implode(", ", $categoryNames);
        }

        return $blog;
    }

    /**
     * Undocumented function
     *
     * @param [type] $requestedData
     * @param [type] $blog
     * @return void
     */
    public static function update($requestedData, $blog)
    {
        $blog->update($requestedData);
        $blog->categories()->delete();

        $categories = [];
        $categoryIds = explode(',', $requestedData['categories']);
        foreach ($categoryIds as $key => $category) {
            $categories[] = [
                'blog_id' => $blog->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        BlogCategory::insert($categories);

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $blog
     * @return void
     */
    public static function delete($blog)
    {
        $blog->categories()->delete();
        $blog->delete();

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
        BlogCategory::whereIn('blog_id', $ids)->delete();
        Blog::whereIn('id', $ids)->delete();

        return true;
    }

}
