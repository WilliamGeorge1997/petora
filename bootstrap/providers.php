<?php

use App\Providers\AppServiceProvider;
use App\Providers\EnvKitTrustProxies;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    TelescopeServiceProvider::class,
    EnvKitTrustProxies::class,
];
