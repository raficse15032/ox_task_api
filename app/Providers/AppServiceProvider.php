<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('base64_jpg', function ($attribute, $value, $parameters, $validator) {
            $exploded = explode(',', $value);
            $extension = array(explode('/', mime_content_type($value))[1]);
            $keyword = array('jpg','jpeg','png','gif');
            if(0 < count(array_intersect(array_map('strtolower', $extension), $keyword))){
                return true;
            }
            else{
                return false;

            }
        });

        Validator::extend('base64_size', function ($attribute, $value, $parameters, $validator) {
            $exploded = explode(',', $value);
            if((int)(strlen(base64_decode($exploded[1]))/1024) <= 1024){
                return true;  
            }
            else{
               return false;
            }
        });

    }
}

