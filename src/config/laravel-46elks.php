<?php

return [
    // E.164 number or text
    // se more at: https://46elks.se/kb/e164 and https://46elks.se/kb/text-sender-id
    'from' => env('e46ELKS_FROM', config('app.name')),

    // From main or sub-acccount
    'username' => env('e46ELKS_USERNAME'),
    'password' => env('e46ELKS_PASSWORD'),

    // Will attempt to clean numbers. For example "070-147 (55) 17" will transformed to "+46701475517" before dispatch
    // This will NOT affect the from field.
    'transform_recipients' => env('e46ELKS_TRANSFORM_RECIPIENTS', false),
    'transform_cc' => env('e46ELKS_TRANSFORM_CC', '+46'),

    // only applies to SMS. Dry-run is a server-sided test that costs 0 (great for testing)
    'dry_run' => env('e46ELKS_DRY_RUN', false),

    // Post-hooks
    'when_delivered' => env('e46ELKS_WHEN_DELIVERED', false),

    // 46elks ipv4 origins for Authenticate46ElksRequests. Default values found at https://46elks.com/faq section
    'ipv4_origins' => env('e46ELKS_IPV4_ORIGINS', '176.10.154.199|85.24.146.132|185.39.146.243'),
    'ipv6_origins' => env('e46ELKS_IPV6_ORIGINS', '2001:9b0:2:902::199'),
];
