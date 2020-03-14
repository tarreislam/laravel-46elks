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
    'when_delivered' => env('e46ELKS_WHEN_DELIVERED', false),

    // 46 elks ipv4 origins for Authenticate46ElksRequests. Fetched at  https://46elks.com/faq section "Security"
    'ipv4_origins' => env('e46ELKS_IPV4_ORIGINS', '176.10.154.199|85.24.146.132|185.39.146.243'),

    // 46 elks ipv6 origins for Authenticate46ElksRequests.  Fetched at  https://46elks.com/faq section "Security"
    'ipv6_origins' => env('e46ELKS_IPV6_ORIGINS', '2001:9b0:2:902::199'),
];
