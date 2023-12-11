<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = "news";

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'date'
    ];

    protected $hidden = [];

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    public function categories()
    {
        return $this->hasMany(NewsCategory::class);
    }
}
