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
        'api/pesapal/callback',
        'api/pesapal/ipn',
        'api/revenuecat/webhook',
        'SurveyHub/api/pesapal/callback',
        'SurveyHub/api/pesapal/ipn',
        'SurveyHub/api/revenuecat/webhook',
    ];
}