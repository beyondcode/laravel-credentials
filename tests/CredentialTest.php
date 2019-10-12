<?php

namespace BeyondCode\Credentials\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use Illuminate\Encryption\Encrypter;
use BeyondCode\Credentials\Credentials;
use Illuminate\Contracts\Encryption\DecryptException;
use BeyondCode\Credentials\CredentialsServiceProvider;

class CredentialTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CredentialsServiceProvider::class];
    }

    public function tearDown(): void
    {
        @unlink(__DIR__ . '/temp/credentials.php.enc');
    }

    /** @test */
    public function it_can_load_encrypted_files()
    {
        $masterKey = Str::random(16);

        // create fake credentials
        $encrypter = new Encrypter($masterKey);
        $fakeCredentials = $encrypter->encrypt([
            'key' => 'my-secret-value'
        ]);

        $encryptedData = '<?php return '.var_export($fakeCredentials, true).';';

        file_put_contents(__DIR__ . '/temp/credentials.php.enc', $encryptedData);

        $credentials = new Credentials($encrypter);

        $credentials->load(__DIR__ . '/temp/credentials.php.enc');

        $this->assertSame('my-secret-value', $credentials->get('key'));
    }

    /** @test */
    public function it_can_store_data_encrypted()
    {
        $masterKey = Str::random(16);

        $encrypter = new Encrypter($masterKey);

        $data = [
            'key' => 'my-secret-value'
        ];

        $credentials = new Credentials($encrypter);

        $credentials->store($data, __DIR__ . '/temp/credentials.php.enc');

        $this->assertSame([
            'key' => 'my-secret-value'
        ], $credentials->load(__DIR__ . '/temp/credentials.php.enc'));
    }

    /** @test */
    public function it_returns_decrypted_data()
    {
        $masterKey = Str::random(16);

        // create fake credentials
        $encrypter = new Encrypter($masterKey);
        $fakeCredentials = $encrypter->encrypt([
            'key' => 'my-secret-value'
        ]);

        $encryptedData = '<?php return '.var_export($fakeCredentials, true).';';

        file_put_contents(__DIR__ . '/temp/credentials.php.enc', $encryptedData);

        $credentials = new Credentials($encrypter);

        $decrypted = $credentials->load(__DIR__ . '/temp/credentials.php.enc');

        $this->assertSame([
            'key' => 'my-secret-value'
        ], $decrypted);
    }

    /**
     * @test
     */
    public function it_can_not_decrypt_with_the_wrong_key()
    {
        $this->expectException(DecryptException::class);

        $masterKey = Str::random(16);

        // create fake credentials
        $encrypter = new Encrypter($masterKey);
        $fakeCredentials = $encrypter->encrypt([
            'key' => 'my-secret-value'
        ]);

        $encryptedData = '<?php return '.var_export($fakeCredentials, true).';';

        file_put_contents(__DIR__ . '/temp/credentials.php.enc', $encryptedData);

        $credentials = new Credentials(new Encrypter(Str::random(16)));

        $credentials->load(__DIR__ . '/temp/credentials.php.enc');
    }

    /** @test */
    public function it_can_use_the_helper_function()
    {
        $this->app['config']->set('credentials.file', __DIR__ . '/temp/credentials.php.enc');

        $data = [
            'key' => 'my-secret-value'
        ];

        $credentials = app(Credentials::class);

        $credentials->store($data, __DIR__ . '/temp/credentials.php.enc');

        $this->assertSame('my-secret-value', credentials('key'));
    }

    /** @test */
    public function it_can_give_a_default_to_the_helper_function()
    {
        $this->app['config']->set('credentials.file', __DIR__ . '/temp/credentials.php.enc');

        $data = [
            'key' => 'my-secret-value'
        ];

        $credentials = app(Credentials::class);

        $credentials->store($data, __DIR__ . '/temp/credentials.php.enc');

        $this->assertSame('my-fallback-value', credentials('wrong-key', 'my-fallback-value'));
    }

    /** @test */
    public function it_replaces_credential_strings_in_the_configuration_files()
    {
        $this->app['config']->set('credentials.file', __DIR__ . '/temp/credentials.php.enc');

        $data = [
            'key' => 'my-secret-value'
        ];

        $credentials = app(Credentials::class);

        $credentials->store($data, __DIR__ . '/temp/credentials.php.enc');

        $this->app['config']->set('credentials.secret', Credentials::CONFIG_PREFIX.'key');

        $this->assertSame('my-secret-value', credentials('key'));
    }
}
