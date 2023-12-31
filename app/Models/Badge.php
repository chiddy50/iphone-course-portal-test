<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'required_achievements', 'level'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges', 'badge_id', 'user_id');
    }

    public static function getBadgeMapping()
    {
        return self::pluck('required_achievements', 'name')->toArray();
    }
}
