<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'author_name',
        'image',
        'description',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $hidden = [];

    protected function authorName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         // get: fn (string|null $value) => $value ? '/public' . $value : null,
    //         get: fn (string|null $value) => $value ? 'http://192.168.3.118:8005' . $value : null,
    //     );
    // }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    public function categories()
    {
        return $this->hasMany(BlogCategory::class);
    }
}
