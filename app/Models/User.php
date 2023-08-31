<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements', 'user_id', 'achievement_id');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watched()
    {
        return $this->belongsToMany(Lesson::class, 'user_lessons', 'user_id', 'lesson_id');
    }

    public function hasUnlockedAchievement($achievement_name): bool
    {
        return $this->achievements()->where('name', $achievement_name)->exists();
    }

    public function hasBadge($badge_name): bool
    {
        return $this->badges()->where('name', $badge_name)->exists();
    }

    public function hasWatched($lesson_name): bool
    {
        return $this->watched()->where('title', $lesson_name)->exists();
    }

    public function currentBadge()
    {
        $badge = $this->badges()->orderByDesc('level')->first();
        return $badge ? $badge->name : '';
    }

    public function getNextBadgeName()
    {
        $achievements_count = $this->achievements()->count() ?? 0;

        $next_badge = Badge::where('required_achievements', '>', $achievements_count)
                  ->orderBy('required_achievements', 'asc')
                  ->first();
        return $next_badge ? $next_badge->name : '';
    }



}
