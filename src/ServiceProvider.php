<?php


namespace Tarre\Laravel46Elks;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Tarre\Laravel46Elks\Channels\SmsChannel;
use Tarre\Php46Elks\Client;


class ServiceProvider extends BaseServiceProvider
{


    public function boot()
    {
        /*
         * Publish config
         */
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'laravel-46elks.php' => config_path('laravel-46elks.php'),
        ], 'laravel-46elks');
    }

    public function register()
    {
        /*
         * add "46elks" to Laravel's notification channel drivers
         */
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('46elks', function ($app) {
                // load settings
                $username = config('laravel-46elks.username');
                $password = config('laravel-46elks.password');
                $from = config('laravel-46elks.from');
                $dryRun = config('laravel-46elks.dry_run');
                $whenDelivered = config('laravel-46elks.when_delivered');

                // create client
                $client = new Client($username, $password);

                // create service
                $sms = $client->sms()->from($from);

                // set global settings for each message request
                if ($dryRun) {
                    $sms = $sms->dryRun();
                }

                // post webhook
                if ($whenDelivered) {
                    $sms = $sms->whenDelivered($whenDelivered);
                }

                // return dispatcher instance
                $dispatcher = $sms->dispatcher();

                // return channel
                return new SmsChannel($dispatcher);
            });
        });

    }


    protected function getBaseClient()
    {

    }

}
