<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Helper para doble MD5
        if (!function_exists('double_md5')) {
            function double_md5($value)
            {
                return md5(md5($value));
            }
        }
    }

    public function boot(): void
    {
        // Validadores personalizados
        Validator::extend('double_md5', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-f0-9]{32}$/', $value);
        }, 'El campo :attribute debe estar encriptado con doble MD5.');
    }
}