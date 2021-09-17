<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array {
        return [
            'name' => $this->faker->name(),
            'date_of_birth' => $this->faker->dateTimeBetween('1800-01-01', '2000-01-01')->format('m/d/Y'),
            'date_of_death' => $this->faker->dateTimeBetween('1900-01-01', '2020-01-01')->format('m/d/Y'),
            'country' => $this->faker->country()
        ];
    }
}
