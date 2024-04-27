<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieList extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'slug'
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
