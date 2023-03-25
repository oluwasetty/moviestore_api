<?php

namespace App\Movies;

use App\Models\Movie;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Arr;

class ElasticsearchRepository implements MoviesRepository
{
    /** @var \Elastic\Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query = '', $per_page)
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new Movie;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title^5', 'Author^3', 'genre^1', 'isbn', 'publisher', 'published'],
                        'query' => $query,
                    ],
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items)
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Movie::findMany($ids)
            ->sortBy(function ($movie) use ($ids) {
                return array_search($movie->getKey(), $ids);
            });
    }
}