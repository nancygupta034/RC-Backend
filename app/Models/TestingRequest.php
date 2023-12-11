<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'testing_type_id',
        'first_name',
        'last_name',
        'email',
        'home_phone',
        'mobile_number',
        'dob',
        'relation_to_patient',
        'clinician_preference',
        'message',
        'insurance_or_selfpay',
        'insurance_id',
        'insurance_plan_id',
        'member_id',
        'policy_holder_gender',
        'hear_about_us'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    public function testingType()
    {
        return $this->belongsTo(TestingType::class);
    }

    public function insurance()
    {
        return $this->belongsTo(TruBillingInsurance::class, 'insurance_id', 'insurance_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'insurance_plan_id', 'plan_id');
    }
}
