<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => '2023-04-10',
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'user_id' => 1,
            'patient_id' => 2,
            'service_id' => 21,
        ];
    }
}
