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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watched()
    {
        return $this->belongsToMany(Lesson::class, 'user_lessons', 'user_id', 'lesson_id');
    }

    public function hasUnlockedAchievement($achievementName): bool
    {
        return $this->achievements()->where('name', $achievementName)->exists();
    }

    public function getNextAvailableAchievements()
    {
        // Get the names of unlocked achievements
        $unlockedAchievements = $this->achievements->pluck('name')->toArray();

        // Define the available achievements for lessons and comments
        $lessonsAvailableAchievements = [
            'First Lesson Watched',
            '5 Lessons Watched',
            '10 Lessons Watched',
            '25 Lessons Watched',
            '50 Lessons Watched',
        ];

        $commentsAvailableAchievements = [
            'First Comment Written',
            '3 Comments Written',
            '5 Comments Written',
            '10 Comments Written',
            '20 Comments Written',
        ];

        // Determine the next available achievements
        $nextAvailableAchievements = [];
        foreach ($lessonsAvailableAchievements as $achievement) {
            if (!in_array($achievement, $unlockedAchievements)) {
                $nextAvailableAchievements[] = $achievement;
                break; // Only show one next achievement per group
            }
        }

        foreach ($commentsAvailableAchievements as $achievement) {
            if (!in_array($achievement, $unlockedAchievements)) {
                $nextAvailableAchievements[] = $achievement;
                break; // Only show one next achievement per group
            }
        }

        return $nextAvailableAchievements;
    }

    public function currentBadge()
    {
        // if ($this->badge_id) {
        //     return Badge::find($this->badge_id)->name;
        // }

        return null;
    }

    public function getNextBadgeName()
    {
        // $nextBadge = Badge::where('required_achievements', '>', $this->achievements()->count())
        // ->orderBy('required_achievements')
        // ->first();

        // return $nextBadge ? $nextBadge->name : null;

        $badgeMapping = [
            'Beginner' => 0,
            'Intermediate' => 4,
            'Advanced' => 8,
            'Master' => 10,
        ];

        foreach ($badgeMapping as $badgeName => $requiredAchievements) {
            if ($this->achievements->count() < $requiredAchievements) {
                return $badgeName;
            }
        }

        return 'Master'; // If all achievements are unlocked, user is a Master
    }

    public function remainingAchievementsToUnlockNextBadge()
    {
        // if ($this->badge_id) {
        //     $currentBadge = Badge::find($this->badge_id);
        //     $nextBadge = $this->nextBadge();

        //     if ($currentBadge && $nextBadge) {
        //         return $nextBadge->required_achievements - $currentBadge->required_achievements;
        //     }
        // }

        // return null;

        $badgeMapping = [
            'Beginner' => 0,
            'Intermediate' => 4,
            'Advanced' => 8,
            'Master' => 10,
        ];

        $unlockedAchievements = $this->achievements->count();

        foreach ($badgeMapping as $requiredAchievements) {
            if ($unlockedAchievements < $requiredAchievements) {
                return $requiredAchievements - $unlockedAchievements;
            }
        }

        return 0; // If all achievements are unlocked, no more remaining
    }

}
