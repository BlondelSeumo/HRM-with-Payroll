<?php

namespace Arkitecht\Twilio\Providers;

use Arkitecht\Twilio\Twilio;
use Illuminate\Support\ServiceProvider;

class TwilioServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/twilio.php' => config_path('twilio.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Twilio::class, function () {
            return new Twilio(config('twilio.sid'), config('twilio.token'));
        });

        $this->app->alias(Twilio::class, 'twilio');
    }
}
