<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'book-appointment', 'services-provided','clinicians','locations','age-ranges','services','testing-types', 'states', 'specialities', '/services', 'add-testing-request', 'add-referral', 'update-appointment', 'reschedule-appointment'
    ];
}
