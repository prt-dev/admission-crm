<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('apis');
});

Route::get('/apis', function () {
    return view('apis');
});
