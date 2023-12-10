<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $fillable = ['other_columns', 'club_id','club']; // Add 'club_id' here

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id', 'user_id');
    }

    public static function searchPlayers($searchText)
    {
        return static::where('name', 'LIKE', '%' . $searchText . '%')
        ->orWhere('rank', 'like', '%' . $searchText . '%')
        ->orWhere('goals', 'like', '%' . $searchText . '%')
        ->orWhere('assists', 'like', '%' . $searchText . '%')
        ->orWhere('minsplayed', 'like', '%' . $searchText . '%')
        ->orWhere('expeirence', 'like', '%' . $searchText . '%')
        ->get();
    }
    public function clubBids()
    {
        return $this->hasMany(ClubBid::class, 'player_id', 'id');
    }
}
