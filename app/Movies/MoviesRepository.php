<?php

namespace App\Movies;

interface MoviesRepository
{
    public function search(string $query = '', $per_page);
}