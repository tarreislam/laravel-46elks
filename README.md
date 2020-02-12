![php 46elks logo](https://i.imgur.com/32yAGi9.png)

# Laravel-46Elks
#### Laravel notification driver (SMS) for [46elks.se](46elks.se)

### Installation

**Install with composer**

```
compose require tarre/laravel-46elks
```


**Add Service provider**

Add `Tarre\Laravel46Elks\ServiceProvider::class` `config/app.php` if your Laravel app does not support auto-discover

**Publish config**

``php artisan vendor:publish --tag="laravel-46elks"``

**Adjust config**

Add this to your `.env` file
```dotenv
46ELKS_FROM=MyApp
46ELKS_USERNAME=xxx
46ELKS_PASSWORD=yyy
46ELKS_DRY_RUN=false
46ELKS_WHEN_DELIVERED=false
```

### Example usage

**User model**
```php

use Illuminate\Notifications\Notifiable;

class User
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

### Available `SmsMessage` methods

* `__construct(array $lines)` alternative to `->line('')` 
* `from($senderId)` overrides the .env file
* `cc($e164Number)` Add more e164 recipients
* `whenDelivered($url)` overrides the .env file
* `flash($state = true)` Flash SMS are shown immediately on the receivers phone and do not disappear until the receiver has confirmed the message. This feature is perfect for messages that really have to be read by the receiver.
