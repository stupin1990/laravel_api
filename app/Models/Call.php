<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Builder;
use App\Traits\ApiPaginator;
use App\Traits\ModelsCommon;

class Call extends Model
{
    use HasFactory, ApiPaginator, ModelsCommon;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
     * Get query for users breaks of calls, more than $break_time in minutes grouped by month of current year
     * @param int $break_time: time in minutes
     * @return array users_id, month, breaks
     */
    public static function getCallBreakesByMonthQuery(int $break_time = 5) : string
    {
        return "SELECT 
            users.email, 
            TO_CHAR(c3.calltime, 'Month') AS month, 
            COUNT(c3.id) AS breaks
            FROM (
                SELECT c1.id, 
                c1.user_id, 
                c1.calltime + c1.duration_sec * interval '1 second' AS calltime,
                (SELECT calltime FROM calls WHERE user_id = c1.user_id AND id > c1.id ORDER BY id ASC LIMIT 1) AS next_calltime
                FROM calls AS c1
                WHERE EXTRACT(YEAR FROM c1.calltime) = EXTRACT(YEAR FROM NOW())
                ORDER BY c1.user_id DESC, c1.calltime DESC
            ) AS c3
            LEFT JOIN users ON users.id = c3.user_id
            WHERE c3.next_calltime - c3.calltime > interval '$break_time minutes'
            GROUP BY c3.user_id, users.email, month
            ORDER BY c3.user_id, month
        ";
    }
}
