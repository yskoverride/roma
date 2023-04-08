<?php

namespace Tests\Feature;

use App\Http\Livewire\DoctorComponent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TenantTestCase;

class DoctorComponentTest extends TenantTestCase
{

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Create a new user and log in
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }
    
    
    /** @test */
    public function main_page_contains_doctor_component()
    {
        $this->get('/doctors')
            ->assertSeeLivewire('doctor-component');
    }

    /** @test */
    public function test_doctor_records_can_be_created()
    {
        Livewire::test(DoctorComponent::class)
            ->set('name', 'John Doe')
            ->set('email', 'john.doe@example.com')
            ->set('phone', '1234567890')
            ->call('store');

        $this->assertTrue(User::where('email', 'john.doe@example.com')->exists());
    }


    /** @test */
    public function doctor_records_can_be_updated()
    {
        $doctor = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' =>  Hash::make('password'),
        ]);

        Livewire::test(DoctorComponent::class)
            ->set('doctorId', $doctor->id)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane.doe@example.com')
            ->set('phone', '0987654321')
            ->call('update');

        $this->assertTrue(User::where('email', 'jane.doe@example.com')->exists());
    }

    /** @test */
    public function doctor_records_can_be_deleted()
    {
        $doctor = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' =>  Hash::make('password'),
        ]);

        Livewire::test(DoctorComponent::class)
            ->call('delete', $doctor->id);

        $this->assertFalse(User::where('email', 'john.doe@example.com')->exists());
    }


    /** @test */
    public function it_searches_doctors_by_name()
    {
        // Create some User records
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);
        User::factory()->create(['name' => 'Alice']);

        // Create and test the component
        Livewire::test(DoctorComponent::class)
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith')
            ->assertDontSee('Alice');
    }

    /** @test */
    public function it_searches_Doctors_by_email()
    {
        // Create some User records
        User::factory()->create(['email' => 'John@test.com']);
        User::factory()->create(['email' => 'Jane@test.com']);
        User::factory()->create(['email' => 'Alic@test.com']);

        // Create and test the component
        Livewire::test(DoctorComponent::class)
            ->set('search', 'John@t')
            ->assertSee('John@test.com')
            ->assertDontSee('Jane@test.com')
            ->assertDontSee('Alic@test.com');
    }

    /** @test */
    public function it_searches_Users_by_phone()
    {
        // Create some User records
        User::factory()->create(['phone' => '+911111111111']);
        User::factory()->create(['phone' => '+912222222222']);
        User::factory()->create(['phone' => '+913333333333']);

        // Create and test the component
        Livewire::test(DoctorComponent::class)
            ->set('search', '111')
            ->assertSee('+911111111111')
            ->assertDontSee('+912222222222')
            ->assertDontSee('+913333333333');
    }
}
