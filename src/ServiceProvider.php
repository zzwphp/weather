<?php
/**
 * Created by PhpStorm.
 * User: zhangzhenwei
 * Date: 2019/5/31
 * Time: 17:04
 */

namespace Ritin\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function(){
            return new Weather(config('services.weather.key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}