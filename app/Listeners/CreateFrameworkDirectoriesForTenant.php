<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenantCreated;

class CreateFrameworkDirectoriesForTenant
{
    public function handle(TenantCreated $event)
    {
        $tenant = $event->tenant;

        $tenant->run(function ($tenant) {
            $storage_path = storage_path();

            mkdir("$storage_path/framework/cache", 0777, true);
        });
    }
}