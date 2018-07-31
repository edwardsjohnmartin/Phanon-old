<?php

namespace App\Providers;

use DB;
use Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // added sql logging to the log file.
        //DB::listen(function($query){
        //    Log::info($query->sql,$query->bindings,$query->time);
        //});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
