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
        * Create PHP46Elks\Client
        */
        $this->app->singleton('PHP46Elks\Client', function () {
            // load settings
            $username = config('laravel-46elks.username');
            $password = config('laravel-46elks.password');
            // create client
            return new Client($username, $password);
        });

        /*
         * Create PHP46Elks\SmsDispatcher service
         */
        $this->app->singleton('PHP46Elks\SmsDispatcher', function ($app) {
            // Load SMS settings
            $from = config('laravel-46elks.from');
            $dryRun = config('laravel-46elks.dry_run');
            $whenDelivered = config('laravel-46elks.when_delivered');

            // get client
            $client = $app->make('PHP46Elks\Client');

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
            return $sms->dispatcher();
        });

        /*
         * add "46elks" to Laravel's notification channel drivers
         */
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('46elks', function ($app) {
                // get configured SMS dispatcher
                $dispatcher = $app->make('PHP46Elks\SmsDispatcher');
                // return channel
                return new SmsChannel($dispatcher);
            });
        });

    }


}
