<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalHealthCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'mental_health_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mentalHealth()
    {
        return $this->belongsTo(MentalHealth::class);
    }
}
