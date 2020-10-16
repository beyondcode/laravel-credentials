<?php

namespace BeyondCode\Credentials;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use BeyondCode\Credentials\Exceptions\InvalidJSON;

class EditCredentialsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'credentials:edit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt and edit existing credentials. They will be decrypted after saving.';

    /**
     * The command handler.
     *
     * @param \BeyondCode\Credentials\Credentials $credentials
     * @return void
     */
    public function handle(Credentials $credentials)
    {
        $filename = config('credentials.file');

        $decrypted = $credentials->load($filename);

        $handle = tmpfile();
        $meta = stream_get_meta_data($handle);

        fwrite($handle, json_encode($decrypted, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT));

        $editor = config('credential.editor', 'vi');

        $process = new Process([$editor, $meta['uri']]);

        $process->setTty(true);
        $process->mustRun();

        $data = json_decode(file_get_contents($meta['uri']), JSON_OBJECT_AS_ARRAY);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw InvalidJSON::create(json_last_error());
        }

        $credentials->store($data, $filename);

        $this->info('Successfully updated credentials.');
    }
}
