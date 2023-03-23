<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        config(['tenancy.database.prefix' => 'test_tenant']);
    }

    protected function createTenant(string $id = '', string $domain = null ): Tenant
    {
        $id = $id ?? Str::random('10');
        $domain = $domain ?? $id . '.' .config(['tenancy.central_domains'][2]);
        return (Tenant::create(['id' => $id]))->domains(['domain' => $domain]);
    }
}
