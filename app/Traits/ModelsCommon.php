<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ModelsCommon
{
    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get model query builder data
     * @param array $select - items for 'select' function
     * @param array $params - items for 'where' function
     * @param array $with - items for 'with' function
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function getDataByParams(array $select = [], array $with = [], array $params = []) : Builder
    {
        $results = static::with($with)->when(count($select), function ($query) use ($select) {
            return $query->select($select);
        });
        foreach ($params as $param => $value) {
            $results->when($value, function ($query) use ($param, $value) {
                return $query->where($param, $value);
            });
        }
        return $results;
    }
}