<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Status;
use App\User;
use Auth;
use App\Follower;

class FollowersController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth', [
          'store', 'destroy'
      ]);
  }

  public function store($id)
  {
      $user = User::findOrFail($id);

      if (Auth::user()->id === $user->id) {
          return redirect('/');
      }

      if (!Auth::user()->isFollowing($id)) {
          Auth::user()->follow($id);
      }

      return redirect()->route('user.show', $id);
  }

  public function destroy($id)
  {
      $user = User::findOrFail($id);

      if (Auth::user()->id === $user->id) {
          return redirect('/');
      }

      if (Auth::user()->isFollowing($id)) {
          Auth::user()->unfollow($id);
      }

      return redirect()->route('user.show', $id);
  }
  public function togglekeepupdate(Request $request)
  {
     $follower = Follower::where([['user_id','=',request('user_id')],['follower_id','=',Auth::id()]])
     ->first();
     if ($follower){
        $follower->keep_updated = !$follower->keep_updated;
        $follower->save();
        return $follower;
     }else{
        return "notwork";
     }
  }
}
