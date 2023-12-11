<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalHealthImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'mental_health_id',
        'image'
    ];
}
