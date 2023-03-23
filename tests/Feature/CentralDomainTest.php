<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CentralDomainTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_central_domain_can_be_accessed(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_new_tenants_can_be_created_from_central_domain()
    {
        $tenant1 = Tenant::create(['id' => 'foo']);

        $tenant1->domains()->create(['domain' => 'foo.roma.test']);

        $response = $this->get('http://foo.roma.test/tenant');

        $response->assertStatus(200);

    }
}
