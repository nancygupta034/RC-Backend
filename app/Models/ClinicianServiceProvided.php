<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicianServiceProvided extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinician_id',
        'service_provided_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function serviceProvided()
    {
        return $this->belongsTo(ServiceProvided::class);
    }
}
