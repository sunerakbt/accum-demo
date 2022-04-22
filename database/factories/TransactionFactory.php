<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "transaction_id" => rand(1000000, 9999999),
            "amount" => $this->faker->randomNumber(rand(3, 6)),
            "is_proccessed" => 0,
        ];
    }
}
