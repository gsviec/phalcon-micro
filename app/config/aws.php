<?php
use Phalcon\Config;

return new Config([
   'aws' => [
       'region' => env('AWS_REGION'),
       'key'    => env('AWS_ACCESS_KEY'),
       'secret' => env('AWS_SECRET_KEY'),
       'bucket' => env('AWS_BUCKET')
   ]
]);
