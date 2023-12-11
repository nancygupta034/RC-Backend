<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_header',
        'primary_image',
        'secondary_image',
        'content',
        'first_counter_label',
        'first_counter_value',
        'second_counter_label',
        'second_counter_value',
        'third_counter_label',
        'third_counter_value'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
