<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateCovered extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'state_id',
        'name',
        'status'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
