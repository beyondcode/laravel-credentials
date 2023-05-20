<?php

use BeyondCode\Credentials\Credentials;
use Illuminate\Contracts\Container\BindingResolutionException;

if (! function_exists('credentials')) {
    /**
     * Get a an encrypted value.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function credentials(string $key, $default = null)
    {
        $filename = config('credentials.file');
        try {
            $credentials = app(Credentials::class);
            $credentials->load($filename);

            return $credentials->get($key, $default);

        } catch (ReflectionException | BindingResolutionException $e) {

            return Credentials::CONFIG_PREFIX . $key;

        }

    }
}
