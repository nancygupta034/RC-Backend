<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'dr_name',
        'clinic_name',
        'dr_email',
        'dr_mobile_number',
        'clinic_mobile_number',
        'clinic_email',
        'tax_id',
        'clinic_address',
        'type',
        'reason',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function drName(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
