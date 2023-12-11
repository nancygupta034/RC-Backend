<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicianState extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinician_id',
        'state_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
