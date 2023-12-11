<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\ClinicianInsurance;

class Clinician extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'qualification',
        'image',
        'bio',
        'azalea_id',
        'email',
        'phone_no',
        'tru_billing_id',
        'charges_per_session',
        'status',
        'is_insurance',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'location',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string|null $value) => $value ? '/public' . $value : null,
    //     );
    // }

    protected function qualification(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ageCovered()
    {
        return $this->hasMany(ClinicianAgeCovered::class);
    }

    public function specialties()
    {
        return $this->hasMany(ClinicianSpecialty::class);
    }

    public function states()
    {
        return $this->hasMany(ClinicianState::class);
    }

    public function locations()
    {
        return $this->hasMany(ClinicianLocation::class);
    }

    public function servicesProvided()
    {
        return $this->hasMany(ClinicianServiceProvided::class);
    }

    public function insurances()
    {
        return $this->hasMany(ClinicianInsurance::class, 'clinician_id', 'tru_billing_id');
    }
}
