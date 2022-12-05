<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'tgl_lahir' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'alamat' => $this->faker->address,
            'no_tlp' => 123456789,
            'jenkel' => $this->faker->randomElement(['L', 'P']),
            'user_id' => 1,
        ];
    }
}
