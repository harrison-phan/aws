<?php

namespace Si6\Aws;

use Illuminate\Support\ServiceProvider;

class AwsServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $services = [
        'Si6\Aws\Contracts\PinpointService' => 'Si6\Aws\Utils\Pinpoint',
        'Si6\Aws\Contracts\SnsService' => 'Si6\Aws\Utils\Sns',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->services as $key => $service) {
            $this->app->singleton($key, $service);
        }
    }
}
