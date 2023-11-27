<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Player;

use App\Models\Club;





class HomeController extends Controller
{

    public function index()
{
            $clubs = Club::all();
            $players = Player::orderBy('rank', 'asc')->get();
            return view('home.homepage',compact('clubs','players'));
}

    public function redirect()
    {
    	$usertype=Auth::user()->usertype;

    	if ($usertype=='1')
    	{
    		return view('admin.home');
    	}
        elseif ($usertype=='2')
    	{
    		return view('club.home');
    	}
    }

}