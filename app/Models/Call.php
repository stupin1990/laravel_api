<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Call extends Model
{
    use HasFactory;

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

    public static function getCallsBreaksByMonth(int $break_time = 5)
    {
        return DB::select("SELECT user_id, EXTRACT(MONTH FROM calltime) AS month, COUNT(id) AS breaks
            FROM (
                SELECT c1.id, 
                c1.user_id, 
                c1.calltime + c1.duration_sec * interval '1 second' AS calltime,
                (SELECT calltime FROM calls WHERE user_id = c1.user_id AND id > c1.id ORDER BY id ASC LIMIT 1) AS next_calltime
                FROM calls AS c1
                ORDER BY c1.user_id DESC, c1.calltime DESC
            ) AS c3
            WHERE EXTRACT(YEAR FROM calltime) = EXTRACT(YEAR FROM NOW())
                AND next_calltime - calltime > interval '$break_time minutes'
            GROUP BY user_id, EXTRACT(MONTH FROM calltime)
            ORDER BY user_id ASC
        ");
    }
}
