<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array {
        return [
            'title' => $this->faker->text(30),
            'description' => $this->faker->realTextBetween(100),
            'published_date' => $this->faker->dateTimeBetween('1900-01-01', '2020-01-01'),
            'author_id' => 5
        ];
    }
}
