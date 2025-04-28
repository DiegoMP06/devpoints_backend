<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isCreatorOfTheContest(Contest $contest)
    {
        return $this->id === $contest->user_id;
    }

    public function isEvaluatorOfTheContest(Contest $contest)
    {
        return $this->id === $contest->user_id || $contest->evaluators()->where('user_id', $this->id)->exists();
    }

    public function contests()
    {
        return $this->hasMany(Contest::class);
    }

    public function evaluatedContests()
    {
        return $this->belongsToMany(Contest::class, 'contest_users', 'user_id', 'contest_id')->withPivot(['id']);
    }

    public function favorites()
    {
        return $this->belongsToMany(Contest::class, 'favorite_contests', 'user_id', 'contest_id')->withPivot(['id']);
    }
}
