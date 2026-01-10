<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Validadores personalizados
        Validator::extend('double_md5', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-f0-9]{32}$/', $value);
        }, 'El campo :attribute debe estar encriptado con doble MD5.');
    }
}