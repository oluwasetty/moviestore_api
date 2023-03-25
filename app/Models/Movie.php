<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies';

    public function episodes(){
        return $this->hasMany('\App\Models\Episode');
    }

    public function alias(){
        return $this->hasMany('\App\Models\Alias', 'titleId', 'tconst');
    }
}
