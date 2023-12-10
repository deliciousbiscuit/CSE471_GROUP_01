<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubBid extends Model
{
    use HasFactory;
    protected $fillable = ['club_id', 'player_id', 'bid_number', 'is_accepted', 'is_declined'];

    protected $table = 'club_bids';

    public function club()
    {
//return $this->belongsTo(Club::class, 'club_id', 'user_id')->withDefault();
    return $this->belongsTo(Club::class, 'club_id', 'user_id');
    }
    

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
