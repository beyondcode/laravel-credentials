<?php

return [

    /*
     * Defines the file that will be used to store and retrieve the credentials.
     */
    'file' => config_path('credentials.php.enc'),

    /*
     * Defines the key that will be used to encrypt / decrypt the credentials.
     * The default is your application key. Be sure to keep this key secret!
     */
    'key' => config('app.key'),

    'cipher' => config('app.cipher'),

    'editor' => env('EDITOR', 'vi'),

    'editorParams' => env('EDITOR_PARAMS'),

    'timeout' => env('EDITOR_TIMEOUT', 60)

];
