<?php

namespace BeyondCode\Credentials;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Encryption\Encrypter;
use BeyondCode\Credentials\Exceptions\FileDoesNotExist;

class Credentials
{
    const CONFIG_PREFIX = '___credentials_';

    /**
     * The encrypter.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    private $encrypter;

    /**
     * The decrypted values array.
     *
     * @var array
     */
    private $decrypted;

    /**
     * Create a new Credentials Instance.
     *
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     * @return void
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Load the file.
     *
     * @param string $filename
     * @return array
     */
    public function load(string $filename)
    {
        if (!file_exists($filename)) {
            $this->decrypted = [];

            return $this->decrypted;
        }

        $encrypted = require($filename);

        $this->decrypted = (array)$this->encrypter->decrypt($encrypted);

        return $this->decrypted;
    }

    /**
     * Store and encrypt the data in the file location.
     *
     * @param array $data
     * @param string $filename
     * @return void
     */
    public function store(array $data, string $filename)
    {
        $credentials = $this->encrypter->encrypt($data);

        $encryptedData = '<?php return ' . var_export($credentials, true) . ';';

        file_put_contents($filename, $encryptedData);
    }

    /**
     * Get an encrypter value.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->decrypted, $key, $default);
    }
}
