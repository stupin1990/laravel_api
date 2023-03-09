<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\ApiPaginator;

class Comment extends Model
{
    use HasFactory, ApiPaginator;

    protected $fillable = [
        'user_id',
        'post_id',
        'content'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get paginated comments or comments of given user / post
     * @param array $params
     * @param array $with
     * @param array $per_page
     * 
     * @return array
     */
    public static function getCommentsByParams(array $params = [], array $with = [], int $per_page = 5) : array
    {
        $results = static::with($with);
        foreach ($params as $param => $value) {
            $results->when($value, function ($query) use ($param, $value) {
                return $query->where($param, $value);
            });
        }
        return $results->apiPaginate($per_page);
    }
}
