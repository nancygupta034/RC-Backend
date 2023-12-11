<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicianAgeCovered extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinician_id',
        'age_range_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function ageRange()
    {
        return $this->belongsTo(AgeRange::class);
    }

}
