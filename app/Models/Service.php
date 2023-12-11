<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'sub_title',
        'slug',
        'status',
        'image',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    protected function subTitle(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string|null $value) => $value ? '/public' . $value : null,
    //         // get: fn (string|null $value) => $value ? 'http://192.168.3.118:8005' . $value : null,
    //     );
    // }
}
