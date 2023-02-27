<?php

namespace App\Traits;

trait ApiPaginator
{
    public function scopeApiPaginate($query, int $per_page = 5)
    {
        $items = $query->paginate($per_page)->toArray();

        return [
            'data' => $items['data'],
            'current_page' => $items['current_page'],
            'last_page' => $items['last_page'],
            'per_page' => $items['per_page'],
            'total' => $items['total']
        ];
    }
}