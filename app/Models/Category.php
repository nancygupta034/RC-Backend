<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'status',
        'type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }
}
