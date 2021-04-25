<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "year",
        "rated",
        "genre",
        "director",
        "actors",
        "plot",
        "poster"
    ];

    public function lists()
    {
        return $this->belongsToMany(MovieList::class);
    }
}
