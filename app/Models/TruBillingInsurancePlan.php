<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TruBillingInsurancePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tru_billing_insurance_id',
        'plan_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'planname'
    ];

    protected function planname(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->plan  ? $this->plan->name : ""),
        );
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'plan_id');
    }

    public function insurance()
    {
        return $this->belongsTo(TruBillingInsurance::class, 'tru_billing_insurance_id', 'insurance_id');
    }
}
