<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use App\Services\ModelHelper;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view){
            $setting = SiteSetting::find('1');
            $menu_categories = ModelHelper::getFullListFromDB('categories');
            return $view->with('setting', $setting)->with('menu_categories', $menu_categories);
        });
    }
}
