<?php

namespace BeyondCode\Credentials;

use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;

class CredentialsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/credentials.php' => config_path('credentials.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/credentials.php', 'credentials');

        // Update configuration strings
        if (!app()->configurationIsCached()) {
            $this->fixConfig();
        }
    }

    protected function fixConfig()
    {
        collect(array_dot(config()->all()))->filter(function ($item) {
            return is_string($item) && starts_with($item, Credentials::CONFIG_PREFIX);
        })->map(function ($item, $key) {
            $item = str_replace_first(Credentials::CONFIG_PREFIX, '', $item);

            config()->set($key, credentials($item));
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(Credentials::class, function () {

            // If the key starts with "base64:", we will need to decode the key before handing
            // it off to the encrypter. Keys may be base-64 encoded for presentation and we
            // want to make sure to convert them back to the raw bytes before encrypting.
            if (Str::startsWith($key = config('credentials.key'), 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            $encrypter = new Encrypter($key, config('credentials.cipher'));
            return new Credentials($encrypter);
        });

        $this->app->bind('command.credentials.edit', EditCredentialsCommand::class);

        $this->commands([
            'command.credentials.edit'
        ]);
    }
}
