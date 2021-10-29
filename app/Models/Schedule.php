<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "isPublic",
        "name",
        "owner",
        "slug",
        "uuid",
        "schedule_id"
    ];

    public function showings()
    {
        return $this->hasMany(Showing::class)
            ->orderBy('show_time');
    }

    public function public_showings()
    {
        return $this->hasMany(Showing::class)
            ->where('isPublic', true)
            ->orderBy('show_time');
    }
}
