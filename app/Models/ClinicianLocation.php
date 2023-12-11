<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicianLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinician_id',
        'location_id',
        'azalea_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
