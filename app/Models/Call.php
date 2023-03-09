<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Builder;
use App\Traits\ApiPaginator;

class Call extends Model
{
    use HasFactory, ApiPaginator;

    protected $fillable = [
        'user_id',
        'calltime',
        'duration_sec'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get breakes for users from calls grouped by month of this year
     * @param array $users: [users_id]
     * @param int $break_time: time in seconds
     * @return array users_id, month, breaks
     */
    public static function getUsersCallsBreaksByMonth(array $users = [], int $break_time = 5) : array
    {
        if (empty($users)) {
            return [];
        }

        $results = [];
        collect(DB::select("SELECT users.email, EXTRACT(MONTH FROM c3.calltime) AS month, COUNT(c3.id) AS breaks
            FROM (
                SELECT c1.id, 
                c1.user_id, 
                c1.calltime + c1.duration_sec * interval '1 second' AS calltime,
                (SELECT calltime FROM calls WHERE user_id = c1.user_id AND id > c1.id ORDER BY id ASC LIMIT 1) AS next_calltime
                FROM calls AS c1
                WHERE EXTRACT(YEAR FROM c1.calltime) = EXTRACT(YEAR FROM NOW())
                    AND c1.user_id IN (" . implode(',', $users) . ")
                ORDER BY c1.user_id DESC, c1.calltime DESC
            ) AS c3
            LEFT JOIN users ON users.id = c3.user_id
            WHERE c3.next_calltime - c3.calltime > interval '$break_time minutes'
            GROUP BY c3.user_id, users.email, EXTRACT(MONTH FROM c3.calltime)
            ORDER BY c3.user_id, EXTRACT(MONTH FROM c3.calltime)
        "))->map(function ($item) use (&$results) {
            $results[] = [
                'email' => $item->email, 
                'month' => date('F', strtotime('2000-' . $item->month . '-01')), 
                'breaks' => $item->breaks
            ];
        });

        return $results;
    }

    /**
     * Get paginated list of users from calls grouped by month of this year
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param int $per_page
     * @return array
     */
    public static function getPaginatedUsersByMonthOfThisYear(int $per_page = 5) : array
    {
        // Get with pagination grouped users and monthes for current year
        return static::selectRaw("user_id, EXTRACT(MONTH FROM calltime) AS month")
            ->whereRaw("EXTRACT(YEAR FROM calltime) = EXTRACT(YEAR FROM NOW())")
            ->groupByRaw("user_id, EXTRACT(MONTH FROM calltime)")
            ->orderBy('user_id')
            ->orderBy('month')
            ->apiPaginate($per_page);
    }
}
