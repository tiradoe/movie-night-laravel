<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovieList;

class MovieListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MovieList::factory()
            ->count(20)
            ->hasMovies(10)
            ->create();
    }
}
