<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Tests\TenantTestCase;

class PatientTest extends TenantTestCase
{
    public function testPatientCreationAndRetrieval()
    {
        // Create a patient
        $patient = Patient::factory()->create();

        // Retrieve the patient from the database
        $retrievedPatient = Patient::find($patient->id);

        // Check if the retrieved patient's data matches the created patient's data
        $this->assertEquals($patient->id, $retrievedPatient->id);
        $this->assertEquals($patient->name, $retrievedPatient->name);
        $this->assertEquals($patient->email, $retrievedPatient->email);
        $this->assertEquals($patient->phone, $retrievedPatient->phone);
    }

    // public function testRelationshipBetweenPatientsAndUsers()
    // {
    //     // Create a user
    //     $user = User::factory()->create();

    //     // Create multiple patients and associate them with the user
    //     $patients = Patient::factory()->count(3)->create(['user_id' => $user->id]);

    //     // Check if the created patients are related to the user
    //     $retrievedPatients = $user->patients;
    //     $this->assertCount(3, $retrievedPatients);

    //     // Check if the patient's user matches the created user's data
    //     foreach ($retrievedPatients as $patient) {
    //         $patientUser = $patient->user;
    //         $this->assertEquals($user->id, $patientUser->id);
    //         $this->assertEquals($user->name, $patientUser->name);
    //         $this->assertEquals($user->email, $patientUser->email);
    //     }
    // }


}
