<?php

return [
    'secret' => env('JWT_SECRET'),

    'algorithm' => 'HS256',

    'ttl' => 60 * 60, // 1 hour
];