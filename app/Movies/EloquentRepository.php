<?php

namespace App\Movies;

use App\Models\Movie;

class EloquentRepository implements MoviesRepository
{
    public function search(string $q = '', $per_page)
    {
        $movies = Movie::query()
        ->where(fn ($query) => (
            $query->where('primaryTitle', 'LIKE', "{$q}%")
        ))
        ->paginate($per_page);
        return $movies;
    }
}