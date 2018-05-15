<?php

use BeyondCode\Credentials\Credentials;

if (!function_exists('credentials')) {
    function credentials(string $key, $default = null) {
        $filename = config('credentials.file');

        $credentials = app(Credentials::class);
        $credentials->load($filename);

        return $credentials->get($key);
    }
}