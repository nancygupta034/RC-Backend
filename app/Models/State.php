<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class State extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status'
    ];

    public function coveredBy()
    {
        return $this->hasOne(StateCovered::class);
    }

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
