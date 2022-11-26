<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'nombre' => $this->faker->firstName . " " . $this->faker->lastName,
            'edad' => $this->faker->randomFloat(0,16, 40),
            'empresa_id' => $this->faker->randomElement(DB::table('empresas')->pluck('id')),
        ];

    }
}
