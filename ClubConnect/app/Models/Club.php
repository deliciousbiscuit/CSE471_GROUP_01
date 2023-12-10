<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    protected $fillable = ['club_name', 'club_location', 'user_id', 'club_logo'];

    public function club()
    {
        return $this->hasOne(Club::class);
    }
    
    protected $table = 'clubs';

    public function players()
    {
        return $this->hasMany(Player::class, 'club', 'club_name');
    }
      // Your existing code...

      public function clubBids()
      {
          return $this->hasMany(ClubBid::class, 'club_id', 'user_id');
      }
    
    
}
