<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MentalHealth extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'youtube_link',
        'external_link',
        'video',
        'file'
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
        return $this->hasMany(MentalHealthCategory::class);
    }

    public function images()
    {
        return $this->hasMany(MentalHealthImage::class);
    }
}
