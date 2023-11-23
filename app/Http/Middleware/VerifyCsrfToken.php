<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    // public function __construct() {
    //     $testDate = date('Y-m-d H:i:s', strtotime(\Carbon\Carbon::now())+30*60*60*24);
    //     \Carbon\Carbon::setTestNow($testDate);
    // }
}
