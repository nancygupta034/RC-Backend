<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AgeRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_age',
        'max_age',
        'status',
        'icon'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'range','text'
    ];

    protected function range(): Attribute
    {
        if($this->max_age == 0) {
            return Attribute::make(
                get: fn () => ($this->min_age . '+'),
            );
        }
        else {
            return Attribute::make(
                get: fn () => ($this->min_age . '-' . $this->max_age),
            );
        }
    }

    // protected function text(): Attribute
    // {
    //     if($this->min_age == 0) {
    //         return Attribute::make(
    //             get: fn () => ($this->name .' ('. $this->max_age . ' and under)'),
    //         );
    //     }
    //     elseif($this->max_age == 0) {
    //         return Attribute::make(
    //             get: fn () => ($this->name .' ('. $this->min_age . '+)'),
    //         );
    //     }
    //     else {
    //         return Attribute::make(
    //             get: fn () => ($this->name .' ('. $this->min_age . ' - ' . $this->max_age.')'),
    //         );
    //     }
        
    // }

    protected function text(): Attribute
    {
        if($this->min_age == 0) {
            return Attribute::make(
                get: fn () => ($this->max_age . ' and under'),
            );
        }
        elseif($this->max_age == 0) {
            return Attribute::make(
                get: fn () => ($this->min_age . '+'),
            );
        }
        else {
            return Attribute::make(
                get: fn () => ($this->min_age . ' - ' . $this->max_age),
            );
        }
        
    }
}
