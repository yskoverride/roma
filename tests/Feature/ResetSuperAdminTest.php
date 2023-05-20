<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetSuperAdminTest extends TestCase
{
    public function test_reset_superadmin_command()
    {
        // Given we have an old superadmin
        SuperAdmin::create([
            'name' => 'hello',
            'email' => 'oldadmin@example.com',
            'phone' => '+919861414241',
            'password' => bcrypt('password')
        ]);

        // When we run the reset superadmin command
        Artisan::call('app:generate-admin');

        // Then we should have a new superadmin with the admin email from the .env file
        $newAdmin = SuperAdmin::where('email', env('BONKER_EMAIL'))->first();

        $this->assertNotNull($newAdmin);
        $this->assertEquals(1, SuperAdmin::count());
    }
}
