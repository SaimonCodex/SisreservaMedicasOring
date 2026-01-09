<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Hashing\HashManager;

class MD5DoubleHashServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('hash', function ($app) {
            return new HashManager($app);
        });
        
        $this->app->singleton('hash.driver', function ($app) {
            return $app['hash']->driver('md5-double');
        });
    }
    
    public function boot()
    {
        \Hash::extend('md5-double', function () {
            return new class implements \Illuminate\Contracts\Hashing\Hasher {
                public function info($hashedValue)
                {
                    return [];
                }
                
                public function make($value, array $options = [])
                {
                    return md5(md5($value));
                }
                
                public function check($value, $hashedValue, array $options = [])
                {
                    return $this->make($value) === $hashedValue;
                }
                
                public function needsRehash($hashedValue, array $options = [])
                {
                    return false;
                }
            };
        });
    }
}