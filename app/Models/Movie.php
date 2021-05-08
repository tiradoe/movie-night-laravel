<?php

namespace App\Models;

use App\Models\Showing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "imdb_id",
        "year",
        "rated",
        "genre",
        "director",
        "actors",
        "plot",
        "poster"
    ];

    public function movieLists()
    {
        return $this->belongsToMany(MovieList::class);
    }

    public function showings()
    {
        return $this->hasMany(Showing::class);
    }

    public function nextShowing()
    {
        return $this->hasMany(Showing::class)->orderBy("show_time")->limit(1);
    }
}
