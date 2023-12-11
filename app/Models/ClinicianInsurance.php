<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicianInsurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinician_id',
        'insurance_id',
        'plan_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function clinician()
    {
        return $this->belongsTo(Clinician::class, 'clinician_id', 'tru_billing_id');
    }

    public function insurance()
    {
        return $this->belongsTo(TruBillingInsurance::class, 'insurance_id', 'insurance_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'plan_id');
    }

    public function plans()
    {
        return $this->hasMany(ClinicianInsurancePlan::class);
    }
}
