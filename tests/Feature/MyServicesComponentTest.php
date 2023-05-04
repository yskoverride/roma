<?php

namespace Tests\Feature;

use App\Http\Livewire\MyServicesComponent;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Service;
use Tests\TenantTestCase;

class MyServicesComponentTest extends TenantTestCase
{
    /** @test */
    public function it_can_update_users_services()
    {
        // Create a test user and services
        $user = User::factory()->create();
        $service1 = Service::factory()->create(['name' => 'Service 1']);
        $service2 = Service::factory()->create(['name' => 'Service 2']);

        // Attach the first service to the user
        $user->services()->sync([$service1->id]);

        $this->actingAs($user);

        // Refresh the user instance to get the latest related services
        $user->refresh();

        // Create an instance of the MyServicesComponent and test updating the user's services
        Livewire::test(MyServicesComponent::class)
            ->assertSee('Service 1')
            ->assertSee('Service 2')
            ->set('selectedServices', [$service2->id])
            ->call('updateServices')
            ->assertEmitted('displayNotification');

        // Refresh the user instance again to get the updated related services
        $user->refresh();

        // Check if the user's services were updated in the database
        $this->assertTrue($user->services->contains($service2));
        $this->assertFalse($user->services->contains($service1));
    }
}
