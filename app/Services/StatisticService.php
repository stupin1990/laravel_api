<?php

namespace App\Services;

use App\Models\Call;

class StatisticService
{
    protected string $model_name = '';

    /**
     * @param string $model_name
     * @param int $break_time
     * @param int $per_page
     * 
     * @return array
     */
    public function getCallBreakesByMonth(int $break_time, int $per_page): array
    {
        $query = Call::getCallBreakesByMonthQuery($break_time);

        $data = Call::apiPaginateRaw($query, $per_page);

        return $data;
    }
}
