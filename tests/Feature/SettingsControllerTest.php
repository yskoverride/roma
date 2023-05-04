<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TenantTestCase;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;

class SettingsControllerTest extends TenantTestCase
{
    /** @test */
    public function it_returns_the_settings_view_with_authenticated_user()
    {
        // Create a new user and set it as the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Make a request to the settings route
        $response = $this->get(route('settings'));

        // Assert the returned view is 'settings' and contains the authenticated user
        $response->assertViewIs('settings');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_includes_the_app_layout_component_in_the_rendered_view()
    {
        // Create a new user and set it as the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Make a request to the settings route
        $response = $this->get(route('settings'));

        // Assert the response contains the <x-app-layout> component
        $response->assertSee('Services', false);
        $response->assertSee('My Services', false);
        $response->assertSee('My Weekly Schedule', false);
        $response->assertSee('My Unavailabilities', false);
    }


}
