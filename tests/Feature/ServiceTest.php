<?php

namespace Tests\Feature;

use App\Models\User;
use Livewire\Livewire;
use App\Models\Service;
use App\Models\Schedule;
use Tests\TenantTestCase;

class ServiceTest extends TenantTestCase
{
    public function testServiceCreationAndRetrieval()
    {
        // Create a service
        $service = Service::factory()->create();

        // Retrieve the service from the database
        $retrievedService = Service::find($service->id);

        // Check if the retrieved service's data matches the created service's data
        $this->assertEquals($service->id, $retrievedService->id);
        $this->assertEquals($service->name, $retrievedService->name);
        $this->assertEquals($service->duration, $retrievedService->duration);
    }

    public function testRelationshipBetweenServicesAndSchedulesThroughUser()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a service
        $service = Service::factory()->create();

        // Attach the service to the user
        $user->services()->attach($service->id);

        // Create a schedule
        $schedule = Schedule::factory()->create(['user_id' => $user->id]);

        // Retrieve the services of the user
        $userServices = $user->services;

        // Ensure the created service is among the user's services
        $this->assertTrue($userServices->contains($service));

        // Retrieve the schedules of the user
        $userSchedules = $user->schedules;

        // Ensure the created schedule is among the user's schedules
        $this->assertTrue($userSchedules->contains($schedule));
    }

    /** @test */
    public function it_displays_the_services_tab_on_the_settings_page()
    {
        // Create a new user and set it as the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Visit the settings page
        $response = $this->get('/settings');

        // Assert that the services tab is present
        $response->assertSee('Services');
    }

    /** @test */
    public function it_displays_the_service_component_on_the_services_tab()
    {
        // Create a new user and set it as the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Visit the settings page
        $response = $this->get('/settings');

        // Assert that the service component is present
        $response->assertSeeLivewire('service-component');
    }


}
