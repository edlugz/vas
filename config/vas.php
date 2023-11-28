<?php

return [

    /*
   |--------------------------------------------------------------------------
   | Api Key
   |--------------------------------------------------------------------------
   |
   | This value is the api key provided for your developer application.
   | The package needs this to make requests to the VAS APIs.
   |
   */

    'api_key' => env('VAS_API_KEY', ''),
   
   /*
   |--------------------------------------------------------------------------
   | Registered Email Address
   |--------------------------------------------------------------------------
   |
   | The email address under which your user account was registered
   | The package needs this to make requests to the VAS APIs.
   |
   */

    'email' => env('VAS_REGISTERED_EMAIL', ''),

   /*
   |--------------------------------------------------------------------------
   | Sender ID
   |--------------------------------------------------------------------------
   |
   | The email address under which your user account was registered
   | The package needs this to make requests to the VAS APIs.
   |
   */

    'sender_id' => env('VAS_SENDER_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Application URLs
    |--------------------------------------------------------------------------
    |
    | If you will be using the C2B API you can set the URLs that will handle the
    |
    */

    'vas_urls' => [
        'receive_messages' => env('VAS_RECEIVE_MESSAGES_URL', ''),
        'delivery_reports' => env('VAS_DELIVERY_REPORTS_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    |
    | Here you can set your logging requirements. If enabled a new file will
    | will be created in the logs folder and will record all requests
    | and responses to the VAS APIs. You can use the
    | the Monolog debug levels.
    |
    */

    'logs' => [
        'enabled' => true,
        'level' => 'DEBUG',
    ],

];