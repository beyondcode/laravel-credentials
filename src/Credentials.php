<?php

namespace BeyondCode\Credentials;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Encryption\Encrypter;
use BeyondCode\Credentials\Exceptions\FileDoesNotExist;

class Credentials
{

    /** @var Encrypter */
    private $encrypter;

    /** @var array */
    private $decrypted;

    /**
     * Create a new Credentials Instance.
     * @param Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
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
     */
    public function store(array $data, string $filename)
    {
        $credentials = $this->encrypter->encrypt($data);

        $encryptedData = '<?php return ' . var_export($credentials, true) . ';';

        file_put_contents($filename, $encryptedData);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->decrypted, $key, $default);
    }
}
