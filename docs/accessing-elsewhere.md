### Access the 46elks instance elsewhere in your Laravel app

If you want to use the same 46elks instance for something else in your app. You can access the client like so:

```PHP
$client = app('PHP46Elks\Client');
```

Or the SMS service, which has the other `.env` settings as default

```php
$SMSDispatcher = app('PHP46Elks\SmsDispatcher');
```
