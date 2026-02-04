<?php

use Illuminate\Support\Facades\Route;

require_once __DIR__.'/admin.php';
require_once __DIR__.'/vendor.php';
require_once __DIR__.'/customer.php';

Route::get('/', fn () => view('welcome'));
