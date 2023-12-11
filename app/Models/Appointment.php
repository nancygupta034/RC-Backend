<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_id',
        'clinician_id',
        'first_name',
        'last_name',
        'email',
        'home_phone',
        'mobile_number',
        'dob',
        'date',
        'time',
        'service_id',
        'relation_to_patient',
        'hear_about_us',
        'message',
        'status',
        'payment_mode',
        'insurance_id',
        'insurance_plan_id',
        'member_id',
        'policy_holder_gender',
        'cancellation_reason',
        'azalea_patient_id',
        'reason_code',
        'azalea_appointment_id',
        'location',
        'start',
        'end',
        'payment_status',
        'payment_method',
        'referral_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function clinician()
    {
        return $this->belongsTo(Clinician::class);
    }

    public function serviceProvided()
    {
        return $this->belongsTo(ServiceProvided::class, 'service_id', 'id');
    }

    public function locationData()
    {
        return $this->belongsTo(Location::class, 'location', 'azalea_id');
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }

    protected function policyHolderGender(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }

    public function insurance()
    {
        return $this->belongsTo(TruBillingInsurance::class, 'insurance_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(TruBillingInsurancePlan::class, 'insurance_plan_id', 'id');
    }
}
