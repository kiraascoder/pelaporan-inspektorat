<?php

use Illuminate\Support\Facades\Route;


// Home Page Route

Route::get('/', function () {
    return view('home');
});
