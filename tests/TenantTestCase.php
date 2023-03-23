<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Routing\UrlGenerator;

abstract class TenantTestCase extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create tenant and initialize tenancy?
     *
     * @var boolean
     */
    protected $tenancy = true;
    protected $shouldSeed = true;

    public function setUp(): void
    {
        parent::setUp();

        if (! $this->shouldSeed) {
            // Tell the package to use a seeder that does nothing
            config(['tenancy.seeder_parameters.--class' => EmptySeeder::class]);
        }

        if ($this->tenancy) {
            $tenant = $this->createTenant('foo', 'tenant');
            tenancy()->initialize($tenant);

            config(['app.url' => 'http://tenant.localhost']);

            /** @var UrlGenerator */
            $urlGenerator = url();
            $urlGenerator->forceRootUrl('http://tenant.localhost');

            $this->withServerVariables([
                'SERVER_NAME' => 'tenant.localhost',
                'HTTP_HOST' => 'tenant.localhost',
            ]);

            // Login as superuser - optional
            // auth()->loginUsingId(1);
        }
    }
}