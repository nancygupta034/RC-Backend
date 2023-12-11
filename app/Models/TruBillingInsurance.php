<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TruBillingInsurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'insurance_id',
        'name',
        'location'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function clinicianInsurance()
    {
        return $this->belongsTo(ClinicianInsurancePlan::class, 'insurance_id', 'clinician_insurance_id');
    }
}
