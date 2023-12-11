<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'sub_header',
        'left_side_content',
        'right_side_content',
        'primary_image',
        'secondary_image',
        'content',
        'first_counter_label',
        'first_counter_value',
        'second_counter_label',
        'second_counter_value',
        'third_counter_label',
        'third_counter_value',
        'terms_conditions'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
