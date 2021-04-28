<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomNumber(5, true),
            'title' => $this->faker->sentence(),
            'year' => $this->faker->year(),
            'rated' => $this->faker->countryCode(),
            'genre' => $this->faker->word(),
            'director' => $this->faker->name(),
            'actors' => $this->faker->sentence(),
            'plot' => $this->faker->paragraph(),
            //'ratings' => Str::random(10),
            'poster' => $this->faker->url(),
        ];
    }
}
