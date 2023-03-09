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
        $paginated_users = Call::getPaginatedUsersByMonthOfThisYear($per_page);
        
        $users = [];
        collect($paginated_users['data'])->map(function ($item) use (&$users) {
            $users[] = $item['user_id'];
        });

        $items['data'] = Call::getUsersCallsBreaksByMonth($users, $break_time);

        return $items;
    }
}
