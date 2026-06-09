<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tel = str_replace('-', '', fake()->unique()->phoneNumber());
        $address = mb_substr(fake()->address(), 9);

        return [
            'name' => fake()->name(),
            'kana' => fake()->kanaName(),
            'tel' => $tel,
            'email' => fake()->email(),
            'postcode' => fake()->postcode(),
            'address' => $address,
            'birthday' => fake()->dateTime(),
            'gender' => fake()->numberBetween(0, 2),
            'memo' => fake()->realText(50),
        ];
    }
}
