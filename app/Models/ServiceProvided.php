<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ServiceProvided extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
