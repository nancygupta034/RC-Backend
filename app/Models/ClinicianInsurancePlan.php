<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicianInsurancePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinician_insurance_id',
        'plan_id'
    ];

    public function plan()
    {
        return $this->belongsTo(TruBillingInsurancePlan::class, 'plan_id', 'plan_id');
    }
}
