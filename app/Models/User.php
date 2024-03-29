<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\ApiPaginator;
use App\Traits\ModelsCommon;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ApiPaginator, ModelsCommon;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)
            ->orderBy('id', 'desc');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->orderBy('id', 'desc');
    }

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class)
            ->orderBy('id', 'desc');
    }
}
