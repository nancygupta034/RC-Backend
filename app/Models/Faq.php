<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'slug',
        'answer',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $hidden = [];

    protected function question(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    public function categories()
    {
        return $this->hasMany(FaqCategory::class);
    }
}
