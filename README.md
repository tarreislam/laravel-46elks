<p align="center"><img src="https://i.imgur.com/32yAGi9.png"></p>

<p align="center">
<a href="https://packagist.org/packages/tarre/laravel-46elks"><img src="https://img.shields.io/packagist/v/tarre/laravel-46elks?style=flat-square"></a>
<a href="https://packagist.org/packages/tarre/laravel-46elks"><img src="https://img.shields.io/packagist/l/tarre/laravel-46elks?style=flat-square"></a>
</p>

## About Laravel-46Elks

Laravel-46Elks is a SMS notification driver for [Laravel Notifications](https://laravel.com/docs/6.x/notifications), written to streamline the interaction between 46elks API and your Laravel application.
For generic PHP usage, please check out [php-46elks](https://github.com/tarreislam/php-46elks) instead.

### Installation

**Install with composer**

```
composer require tarre/laravel-46elks
```

**Publish config**

``php artisan vendor:publish --tag="laravel-46elks"``

**Adjust config**

Add this to your `.env` file
```dotenv
e46ELKS_FROM=MyApp
e46ELKS_USERNAME=xxx
e46ELKS_PASSWORD=yyy
e46ELKS_DRY_RUN=false
e46ELKS_WHEN_DELIVERED=false
```

### Example usage

**User model**
```php
class User Extends Model
{
    use Notifiable;

    public function routeNotificationFor46elks()
    {
        return '+46701474417'; // could also be an array of strings ['number1', 'number2'] etc
    }
}
```

**Notification**

```php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Tarre\Laravel46Elks\Messages\SmsMessage;

class TestNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['46elks'];
    }


    /**
     * Send the message via 46elks
     *
     * @param mixed $notifiable
     * @return SmsMessage
     */
    public function to46elks($notifiable)
    {
        return (new SmsMessage)
            ->line('Hello ' . $notifiable->name)
            ->line('') // will produce new line
            ->line('Wsup?');
    }
}
````

If you are using `toMail` already, check out [Piggybacking on Notifications toMail method](docs/piggybacking-to-mail.md)

### Available `SmsMessage` methods

* `__construct(array $lines)` Alternative to `->line('')` 
* `->line($line)` Add text message line
* `from($senderId)` Overrides the .env file
* `cc($e164Number)` Add more e164 recipients
* `whenDelivered($url)` Overrides the .env file
* `flash($state = true)` Flash SMS are shown immediately on the receivers phone and do not disappear until the receiver has confirmed the message. This feature is perfect for messages that really have to be read by the receiver.



### More

* [Piggybacking on Notifications toMail method](docs/piggybacking-to-mail.md)
* [Accessing the instance elsewhere in your application](docs/accessing-elsewhere.md)
* [Validate origin on your callbacks](docs/validate-origin-callbacks.md)
* [Validate user input](docs/validate-input.md)
