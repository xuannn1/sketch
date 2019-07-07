<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ResetPasswordNotification;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Helper;

class User extends Authenticatable
{
    use Notifiable;
    use Traits\RegularTraits;
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'lastresponded_at', 'introduction', 'invitation_token', 'majia', 'maximum_qiandao' , 'unread_messages', 'indentation', 'last_quizzed_at', 'qiandao_at', 'quiz_level', 'unread_reminders', 'unread_updates'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'email', 'remember_token','invitation_token',
    ];

    public static function boot()
    {
        parent::boot();
        // static::creating(function ($user) {
        //     $user->activation_token = str_random(30);
        // });
    }
    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    //overriding existing sendpassword reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function intro()
    {
        return $this->hasOne(UserIntro::class, 'user_id');
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role==='admin';
    }

    public function isEditor()
    {
        return $this->role==='editor';
    }

    public function seeHomework()
    {
        return $this->role==='admin'||$this->role==='editor'||$this->role==='senior';
    }

    public function collected_books()//update
    {
        return $this->belongsToMany(Thread::class, 'collections', 'user_id', 'item_id')->wherePivot('collection_list_id', 0)->where('book_id', '>', 0)->withPivot('updated', 'keep_updated');
    }

    public function collected_threads()
    {
        return $this->belongsToMany(Thread::class, 'collections', 'user_id', 'item_id')->wherePivot('collection_list_id', 0)->where('book_id', '=', 0)->withPivot('updated', 'keep_updated');
    }

    public function own_collection_lists()//自己的收藏单
    {
        return $this->hasMany(CollectionList::class,'user_id')->where('type','<>',4);
    }

    public function own_collection_book_lists()//自己的书籍收藏单
    {
        return $this->hasMany(CollectionList::class,'user_id')->where('type','=',1);
    }

    public function own_collection_thread_lists()//自己的讨论帖收藏单
    {
        return $this->hasMany(CollectionList::class,'user_id')->where('type','=',2);
    }

    public function collected_list()//收藏的，别人的收藏单
    {
        return CollectionList::where('type','=',4)->where('user_id', '=', $this->id)->first();
    }

    public function findrecord($post_id)
    {
        return VotePosts::where('user_id', '=', $this->id)->where('post_id', '=', $post_id)->first();
    }
    public function upvotedpost($post_id)
    {
        $record = $this->findrecord($post_id);
        return (($record) && ($record->upvoted));
    }
    public function downvotedpost($post_id)
    {
        $record = $this->findrecord($post_id);
        return (($record) && ($record->downvoted));
    }
    public function funnypost($post_id)
    {
        $record = $this->findrecord($post_id);
        return (($record) && ($record->funny));
    }
    public function foldpost($post_id)
    {
        $record = $this->findrecord($post_id);
        return (($record) && ($record->better_to_fold));
    }

    // public function feed()
    //   {
    //     $user_ids = Auth::user()->followings->pluck('id')->toArray();
    //     array_push($user_ids, Auth::user()->id);
    //     return Status::whereIn('user_id', $user_ids)
    //                             ->with('user')
    //                             ->orderBy('created_at', 'desc');
    //   }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function follow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
    public function checklevelup()
    {
        $level_ups = config('constants.level_up');
        $info = $this->info;
        foreach($level_ups as $level=>$requirement){
            if (($this->level < $level)
            &&(!(array_key_exists('jifen',$requirement))||($requirement['jifen']<=$info->jifen))
            &&(!(array_key_exists('xianyu',$requirement))||($requirement['xianyu']<=$info->xianyu))
            &&(!(array_key_exists('sangdian',$requirement))||($requirement['sangdian']<=$info->sangdian))){
                $this->level = $level;
                $this->save();
                return true;
            }
        }
        return false;
    }
    
    public function linked($id){
        $link1 = Linkaccount::where([['account1','=',$id],['account2','=',$this->id]])->first();
        $link2 = Linkaccount::where([['account2','=',$id],['account1','=',$this->id]])->first();
        return ($link1||$link2);
    }

    public function postreminders()
    {
        return Activity::where('user_id',$this->id)->where('type',1)->where('seen',0)->count();
    }

    public function totalreminders()
    {
        return Activity::where('user_id',$this->id)->where('seen',0)->count();
    }

    public function reward($kind, $base = 0){
        $info = $this->info;
        switch ($kind):
            case "regular_status"://普通状态奖励
            $info->reward(1,1,1,0,0);
            break;
            case "regular_post"://普通回帖奖励
            // $this->increment('experience_points',2);
            // $this->increment('jifen',2);
            // $this->increment('xianyu',1);
            break;
            case "first_post"://抢到新章节首杀
            // $this->increment('experience_points',4);
            // $this->increment('jifen',4);
            // $this->increment('xianyu',2);
            break;
            case "regular_thread"://普通主题奖励
            // $this->increment('experience_points',5);
            // $this->increment('jifen',5);
            // $this->increment('xianyu',3);
            break;
            case "regular_book"://普通书本奖励
            // $this->increment('experience_points',20);
            // $this->increment('jifen',10);
            // $this->increment('xianyu',5);
            // $this->increment('sangdian',2);
            break;
            case "short_chapter"://短小章节奖励
            // $this->increment('experience_points',3);
            // $this->increment('jifen',3);
            // $this->increment('xianyu',1);
            break;
            case "standard_chapter"://标准章节奖励
            // $this->increment('experience_points',5);
            // $this->increment('jifen',5);
            // $this->increment('xianyu',1);
            // $this->increment('sangdian',1);
            break;
            case "regular_post_comment":
            // $this->increment('experience_points',1);
            // $this->increment('jifen',1);
            break;
            case "upvoted_by_many":
            // $this->increment('experience_points',5);
            // $this->increment('jifen',5);
            // $this->increment('xianyu',1);
            // $this->increment('sangdian',1);
            break;
            case "book_downloaded_as_thread":
            // $this->increment('experience_points',5);
            // $this->increment('jifen',5);
            // $this->increment('shengfan',1);
            break;
            case "book_downloaded_as_book":
            // $this->increment('experience_points',10);
            // $this->increment('jifen',10);
            // $this->increment('shengfan',2);
            break;
            case "longcomment":
            // $this->increment('experience_points',5);
            // $this->increment('jifen',5);
            // $this->increment('xianyu',3);
            // $this->increment('sangdian',1);
            break;
            case "homework_excellent":
            // $this->increment('jifen', 100);
            // $this->increment('experience_points', 100);
            // $this->increment('shengfan', 100);
            // $this->increment('xianyu', 50);
            // $this->increment('sangdian', $base*3);
            break;
            case "homework_regular":
            // $this->increment('jifen', 50);
            // $this->increment('experience_points', 50);
            // $this->increment('shengfan', 50);
            // $this->increment('xianyu', 20);
            // $this->increment('sangdian', $base*2);
            break;
            case "online_reward"://保持登陆奖励
            $info->reward(1,0,0,0,0);
            break;
            case "first_quiz":// 首次答题奖励
            // $this->increment('experience_points',10);
            // $this->increment('jifen',10);
            // $this->increment('xianyu',2);
            break;
            case "more_quiz":// 重复答题奖励
            // $this->increment('experience_points',5);
            // $this->increment('shengfan',5);
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
        endswitch;
    }

    public function unreadmessages()
    {
        $unreadmessages = $this->message_reminders
        +$this->post_reminders
        +$this->reply_reminders
        +$this->postcomment_reminders
        +$this->upvote_reminders
        +$this->system_reminders
        +$this->unread_public_notices();
        return $unreadmessages;
    }
    public function unread_public_notices()
    {
        $system_variable = Helper::system_variable();
        $unread_public_notices = $system_variable->latest_public_notice_id - $this->public_notices;
        return $unread_public_notices;
    }

    public function unreadupdates()
    {
        $unreadupdates = $this->collection_books_updated
        + $this->collection_threads_updated
        + $this->collection_statuses_updated
        + $this->collection_lists_updated;
        return $unreadupdates;
    }

    public function isOnline()
    {
        return Cache::has('usr-on-' . $this->id);
    }

    public function linkedaccounts()
    {
        $links = array_merge(
            DB::table('linkaccounts')->where('account2','=',$this->id)->pluck('account1')->toArray(),
            DB::table('linkaccounts')->where('account1','=',$this->id)->pluck('account2')->toArray()
        );
        return DB::table('users')->whereIn('id',$links)->select('id','name')->get();
    }

    public function recent_previous_message()
    {
        if(!Auth::check()){return false;}
        $previous_message = Message::where('poster_id','=',$this->id)
        ->where('receiver_id','=', Auth::id())
        ->latest()
        ->first();
        return $previous_message&&$previous_message->created_at>Carbon::now()->subdays(2)->toDateTimeString();
    }
}
