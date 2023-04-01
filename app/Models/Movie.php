<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Search\Searchable;

class Movie extends Model
{
    use Searchable;
    protected $table = 'movies';

    public function episodes(){
        return $this->hasMany('\App\Models\Episode');
    }

    public function alias(){
        return $this->hasMany('\App\Models\Alias', 'titleId', 'tconst');
    }
}
