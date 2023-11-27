<?php

namespace App\Http\Controllers;

use App\Models\Player;

use App\Models\ClubBid;

use App\Models\Club;

use App\Models\Ranking;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\User;

use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function add_player_page()
    {
        $clubs = Club::all(); 
        return view('admin.Add_Player',compact('clubs'));
    }

    public function add_player_info(Request $request)
    {
    

    $experience = $request->experience;
    $goals = $request->goals;
    $assists = $request->assist;
    $minutesPlayed = $request->minutes_played;

    $rankingValue = $experience + ($goals * 2) + ($assists * 1.5) + ($minutesPlayed / 90);

    $newRankingValue = $rankingValue;
    


    $player = new Player;
    $player->name = $request->name;
    $player->age = $request->age;
    $player->height = $request->height;
    $player->weight = $request->weight;
    $player->contact = $request->contact;
    $player->address = $request->address;
    $player->club_id = Club::where('club_name', $request->club)->value('user_id'); // Assign club_id based on club_name
    //$player->club = $request->club; // Assign club based on club_name
    $player->club = $request->club;
   // $player->club_id = $request->club;
    $player->position = $request->position;
    $player->expeirence = $request->experience;
    $player->goals = $request->goals ?: 0;
    $player->assists = $request->assist ?: 0;
    $player->minsplayed = $request->minutes_played ?: 0;
    $player->ranking_value = $newRankingValue;
    $player->rank = 0;
   
 
    // Handle image upload
    if ($request->hasFile('pimage')) {
        $image = $request->file('pimage');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move('player_images', $imageName);
        $player->pimage = $imageName;
    }
    //$player->club_id = Club::where('club_name', $request->club)->value('user_id'); // Assign club_id based on club_name
   // $player->club = $request->club; 
    $player->save();


    $players = Player::all();
            $existingPlayers = $players->sortByDesc('ranking_value');
            $rank = 1;
            foreach ($existingPlayers as $player) {
                // Update the desired column value for each player
                $player->rank = $rank; 
                $player->save(); // Save the changes to the database
                $rank++;
            }

    
    return redirect()->back()->with('message', 'Player Added Successfully');
    }


    public function track_performance_page()
    {
        $players = Player::all();
        return view('admin.Track_Performance', compact('players'));
    }

    public function update_performance(Request $request, $id)
    {
        $player = Player::find($id);
        if ($player) {
        if ($request->has('goals')) {
            $player->goals = $request->goals;
        }
        if ($request->has('assists')) {
            $player->assists = $request->assists;
        }
        if ($request->has('minsplayed')) {
            $player->minsplayed = $request->minsplayed;
        }
        $experience = $request->experience;
        $goals = $request->goals;
        $assists = $request->assists; 
        $minutesPlayed = $request->minsplayed; 
        $rankingValue = $experience + ($goals * 2) + ($assists * 1.5) + ($minutesPlayed / 90);
        $player->ranking_value = $rankingValue;
        $player->save();

        $players = Player::all();
        $existingPlayers = $players->sortByDesc('ranking_value');
        $rank = 1;
        foreach ($existingPlayers as $existingPlayer) {
            $existingPlayer->rank = $rank;
            $existingPlayer->save(); 
            $rank++;
        }
        }
        


        return redirect()->back()->with('message', 'Performance Updated Successfully');
    }

    public function generate_rating_page()
        {
            $players = Player::orderBy('rank', 'asc')->get(); // sort by rank
            return view('admin.Generate_rating', compact('players'));
        }

    public function find_player_ranking(Request $request)
    {
        // Retrieve the player's name from the request
        $playerName = $request->name;

        // Check if the player exists in the players table
        $player = Player::where('name', $playerName)->first();

        if ($player) {
            // Player exists in the players table
            // Continue saving the ranking information


            // calculate the ranking_value
            $experience = $request->experience;
            $goals = $request->goals;
            $assists = $request->assist;
            $minutesPlayed = $request->minutes_played;

            $rankingValue = $experience + ($goals * 2) + ($assists * 1.5) + ($minutesPlayed / 90);

            $newRankingValue = $rankingValue;



            $ranking = new Ranking;
            $ranking->name = $request->name;
            $ranking->age = $request->age;
            $ranking->height = $request->height;
            $ranking->weight = $request->weight;
            $ranking->playing_position = $request->playing_position;
            $ranking->experience = $request->experience;
            $ranking->goals = $request->goals;
            $ranking->assist = $request->assist;
            $ranking->minutes_played = $request->minutes_played;
            $ranking->ranking_value = $newRankingValue;
            //$ranking->rank = $rank;

            // Handle image upload
            if ($request->hasFile('pimage')) {
                $image = $request->file('pimage');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move('player_images', $imageName);
                $ranking->pimage = $imageName;
            }

            $ranking->save();


            $players = Ranking::all();
            $existingPlayers = $players->sortByDesc('ranking_value');
            $rank = 1;
            foreach ($existingPlayers as $player) {
                // Update the desired column value for each player
                $player->rank = $rank; 
                $player->save(); // Save the changes to the database
                $rank++;
            }


            return redirect()->back()->with('message', 'Player Data Added and Rated Successfully');
        } else {
            // Player does not exist in the players table
            return redirect()->back()->with('error', 'The player does not exist in our database.');
        }
    }
    


    public function showPendingBids()
    {
        $pendingBids = ClubBid::with(['club.clubBids', 'player.clubBids'])
        ->where('is_accepted', false)
        ->where('is_declined', false)
        ->get();

    return view('admin.pending_bids', ['bids' => $pendingBids]);
    }


    public function acceptBid($bidId)
    {
        $bid = ClubBid::findOrFail($bidId);
        $bid->is_accepted = true;
        $bid->save();
        $player = $bid->player->load('club');
      //  dd($player->toArray());
        $newClub = $bid->club;

        // Retrieve the club based on the club name in the player table
       // $club = Club::where('club_name', $player->club)->first();
       // dd($newClub->toArray());
        // Update player's club information
        if ($newClub) {
            $player->update([
                'club_id' => $newClub->user_id,
                'club' => $newClub->club_name,
            ]);
        }
        //dd($player->toArray());
        Session::flash('message', 'Bid accepted');
        return redirect()->back();

        //Session::flash('message', 'Bid accepted');
        //return redirect()->back();
    }

    public function declineBid($bidId)
{
    $bid = ClubBid::findOrFail($bidId);
    $bid->is_declined = true;
    $bid->save();

    Session::flash('message', 'Bid declined');
    return redirect()->back();
}

}
