<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cache;
use Auth;
use Carbon;
use CacheUser;
use ConstantObjects;
use App\Models\Activity;
use App\Sosadfun\Traits\MessageObjectTraits;

class ActivityController extends Controller
{
    use MessageObjectTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        $activity_reminders =  $this->count_activity_reminders($user,$info);
        $messagebox_reminders = $this->count_messagebox_reminders($user,$info);

        $user->clear_column('unread_reminders');

        $activities = Activity::with('item')
        ->withType('post')
        ->withUser($user->id)
        ->orderBy('id', 'desc')
        ->paginate(config('preference.posts_per_page'));

        $activities->load('item.simpleThread','item.author');

        return view('messages.activity_index', compact('user','info','activities','activity_reminders','messagebox_reminders'))->with(['show_message_tab'=>'activities']);
    }

}