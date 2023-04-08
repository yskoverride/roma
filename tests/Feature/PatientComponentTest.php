<?php

namespace Tests\Feature;

use App\Models\User;
use Livewire\Livewire;
use App\Models\Patient;
use Tests\TenantTestCase;
use App\Http\Livewire\PatientComponent;

class PatientComponentTest extends TenantTestCase
{

    public function test_can_create_patient()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(PatientComponent::class)
            ->set('name', 'John Doe')
            ->set('attendant_name', 'Jane Doe')
            ->set('phone', '+919861414121') // Updated phone number format
            ->call('store')
            ->assertHasNoErrors();

        $this->assertTrue(Patient::where('name', 'John Doe')->exists());
    }

    public function test_can_update_patient()
    {
        $this->actingAs(User::factory()->create());

        $patient = Patient::factory()->create([
            'name' => 'John Doe',
            'attendant_name' => 'Jane Doe',
            'phone' => '+919861414122' // Updated phone number format
        ]);

        Livewire::test(PatientComponent::class)
            ->call('edit', $patient->id)
            ->set('name', 'John Updated')
            ->set('attendant_name', 'Jane Updated')
            ->set('phone', '+919861414123') // Updated phone number format
            ->call('update')
            ->assertHasNoErrors();

        $patient->refresh();

        $this->assertEquals('John Updated', $patient->name);
        $this->assertEquals('Jane Updated', $patient->attendant_name);
        $this->assertEquals('+919861414123', $patient->phone); // Updated phone number format
    }

    public function test_can_delete_patient()
    {
        $this->actingAs(User::factory()->create());

        $patient = Patient::factory()->create([
            'name' => 'John Doe',
            'attendant_name' => 'Jane Doe',
            'phone' => '+919861414127'
        ]);

        Livewire::test(PatientComponent::class)
            ->call('delete', $patient->id)
            ->assertHasNoErrors();

        // Check if the patient is soft deleted
        $this->assertTrue($patient->fresh()->trashed());
    }

    /** @test */
    public function it_searches_patients_by_name()
    {
        // Create some patient records
        Patient::factory()->create(['name' => 'John Doe']);
        Patient::factory()->create(['name' => 'Jane Smith']);
        Patient::factory()->create(['name' => 'Alice']);

        // Create and test the component
        Livewire::test(PatientComponent::class)
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith')
            ->assertDontSee('Alice');
    }

    /** @test */
    public function it_searches_patients_by_attendant_name()
    {
        // Create some patient records
        Patient::factory()->create(['attendant_name' => 'John']);
        Patient::factory()->create(['attendant_name' => 'Jane']);
        Patient::factory()->create(['attendant_name' => 'Alice']);

        // Create and test the component
        Livewire::test(PatientComponent::class)
            ->set('search', 'John')
            ->assertSee('John')
            ->assertDontSee('Jane')
            ->assertDontSee('Alice');
    }

    /** @test */
    public function it_searches_patients_by_phone()
    {
        // Create some patient records
        Patient::factory()->create(['phone' => '+911111111111']);
        Patient::factory()->create(['phone' => '+912222222222']);
        Patient::factory()->create(['phone' => '+913333333333']);

        // Create and test the component
        Livewire::test(PatientComponent::class)
            ->set('search', '111')
            ->assertSee('+911111111111')
            ->assertDontSee('+912222222222')
            ->assertDontSee('+913333333333');
    }
}
