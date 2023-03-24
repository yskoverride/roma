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

    public function setUp(): void
    {
        parent::setUp();

        $id = fake()->lastName(). rand(5,99);
        $domain = $id. '.' . 'localhost';
        $fullDomain = 'http://' .$id. '.' . 'localhost';

        if ($this->tenancy) {
            $tenant = $this->createTenant($id,$domain);
            tenancy()->initialize($tenant);

            config(['app.url' => $fullDomain]);

            /** @var UrlGenerator */
            $urlGenerator = url();
            $urlGenerator->forceRootUrl($fullDomain);

            $this->withServerVariables([
                'SERVER_NAME' => $domain,
                'HTTP_HOST' => $domain,
            ]);
        }
    }
}