### Validate origins on your callbacks

A premade middleware is shipped with this package, making it easy to protect your 46elks routes from unwanted origins.

Either in `routes.php` or `api.php`
```php
use Tarre\Laravel46Elks\Middleware\Validate46ElksOrigin;

Route::middleware(Validate46ElksOrigin::class)->group(function () {
    Route::post('C46ElkController@smsDelivered');
    Route::post('C46ElkController@incomingSms');
});
```

or in _app/Http/Kernel.php_

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's route middleware groups.
     *
     * @var array
     */

    protected $middlewareGroups = [
        'web' => [
            // ...
        ],

        'api' => [
            // ...
        ],
        
        '46elks' => [
            \Tarre\Laravel46Elks\Middleware\Validate46ElksOrigin::class   
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        '46elks' => \Tarre\Laravel46Elks\Middleware\Validate46ElksOrigin::class
        // ...
    ];

}
```
or directly in your controllers

```php
<?php

namespace App\Http\Controllers;

use Tarre\Laravel46Elks\Middleware\Validate46ElksOrigin;

class C46ElkController extends Controller
{
    public function __construct()
    {
        $this->middleware(Validate46ElksOrigin::class);
    }

    public function smsDelivered()
    {
        // ...
    }

    public function incomingSms()
    {
        // ...
    }
}
```
