<?php

return [
    // E.164 number or text
    // se more at: https://46elks.se/kb/e164
    'from' => env('e46ELKS_FROM', 'AppName'),

    // From main or sub-acccount
    'username' => env('e46ELKS_USERNAME'),
    'password' => env('e46ELKS_PASSWORD'),

    // only applies to SMS. Dry-run is a server-sided test that costs 0 (great for testing)
    'dry_run' => env('e46ELKS_DRY_RUN', false),

    // Post-hooks
    'when_delivered' => env('e46ELKS_WHEN_DELIVERED', false)
];
