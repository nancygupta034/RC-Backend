<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicianSpecialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinician_id',
        'speciality_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }
}
