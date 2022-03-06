<?php

namespace App\Providers;
use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Calendar::class, function ($app) {
            return new Calendar();
        });

        $this->app->bind(GeneralSetting::class, function ($app) {
            if(GeneralSetting::count() !== 0){
                return GeneralSetting::first();
            }else{
                return new GeneralSetting;
            }
        });
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
