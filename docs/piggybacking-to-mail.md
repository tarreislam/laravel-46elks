### Piggybacking Notifications method `toMail`

It is possible to piggyback laravels default `toMail` notification method to generate an similar text message.

Example:

```php
    public function toMail($notifiable)
    {
        $message = $this->getMessage($notifiable);

        return (new MailMessage)
            ->subject('Great big news');
            ->greeting('Good morning ' . $notifiable->name . '!');
            ->line('Today\'s deals are of the hook')
            ->line('Special price only for you my friend')
            ->line('')
            ->line('Great amirite?')
            ->action('Click here for more information', 'https://google.se');
    }

    public function to46Elks($notifiable)
    {
        return new SmsMessage($this->toMail($notifiable));
    }
```

Will generate:

```
Good morning Tarre!
-
Today's deals are of the hook
Special price only for you my friend

Great amirite?

Click here for more information: https://google.se
```

_The subject is ignored by design_
