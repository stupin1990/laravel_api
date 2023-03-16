<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait ApiPaginator
{
    public function scopeApiPaginate(Builder $query, int $per_page = 5) : array
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

    public static function apiPaginateRaw(string $query, int $per_page = 5) : array
    {
        try {
            $request = app('Illuminate\Http\Request');
            $current_page = $request->input('page', 1);

            $total = DB::select("SELECT COUNT(*) AS total FROM ($query) q")[0]->total;
            $last_page = ceil($total / $per_page);

            $offset = $per_page * ($current_page - 1);
            $data = DB::select($query . " OFFSET $offset LIMIT $per_page");

            return [
                'data' => $data,
                'current_page' => $current_page, 
                'last_page' => $last_page, 
                'per_page' => $per_page, 
                'total' => $total
            ];
        }
        catch (\Exception $e) {
            return [
                'error' => 'Error getting data!'
            ];
        }
    }
    
}