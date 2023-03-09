<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\ApiPaginator;

class Post extends Model
{
    use HasFactory, ApiPaginator;

    protected $fillable = [
        'user_id',
        'title',
        'content',
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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->orderBy('id', 'desc');
    }

    /**
     * Get paginated posts for given user with related data
     * @param int $user_id
     * @param array $with
     * @param int $per_page
     * 
     * @return array
     */
    public static function getPostsForUser(int $user_id, array $with = [], int $per_page = 5)
    {
        return static::with($with)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->apiPaginate($per_page);
    }
}
