<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Models\Status;
use Auth;
use Carbon;
use Cache;
use StringProcess;
use CacheUser;
use App\Sosadfun\Traits\StatusObjectTraits;

class StatusController extends Controller
{
    use StatusObjectTraits;
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'status_body' => 'required|string|max:180'
        ]);
        $status_body = trim($request->status_body);
        $last_status = Status::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        if (!empty($last_status) && strcmp($last_status->body, $status_body) === 0){
            return redirect()->back()->with('warning','您已成功提交状态，请不要重复提交哦！');
        }

        if(($last_status)&&(Carbon::now()->subMinutes(15)->toDateTimeString() < $last_status->created_at )){
            return redirect()->back()->with('warning','15分钟内只能提交一条状态，请等待缓存后提交下一条状态');
        }
        $status = Status::create([
            'user_id' => Auth::id(),
            'body' => $status_body,
            'brief' => StringProcess::trimtext($status_body, 45),
            'is_public' => 1,
        ]);

        return redirect()->route('status.show', $status->id);
    }

    public function destroy(Status $status)
    {
        if($status->user_id != Auth::id()){
            return redirect('/')->with('danger', '动态不存在，请静待缓存更新，无需重复删除！');
        }
        $status->delete();
        return redirect('/')->with('success', '动态已被成功删除！请静待缓存更新');
    }

    public function show($id)
    {
        $status = $this->statusProfile($id);
        if(!$status){
            abort(404);
        }
        $user = Auth::check()? CacheUser::Auser():'';
        $info = Auth::check()? CacheUser::Ainfo():'';
        return view('statuses.show',compact('status','user','info'));
    }

    public function index(Request $request)
    {
        $queryid = 'statusesIndex.'
        .url('/')
        .(is_numeric($request->page)? 'P'.$request->page:'P1');

        $statuses = Cache::remember($queryid, 1, function () use($request) {
            return Status::with('author.title')
            ->isPublic()
            ->ordered()
            ->paginate(config('preference.statuses_per_page'))
            ->appends($request->only('page'));
        });

        return view('statuses.index', compact('statuses'))->with(['status_tab'=>'all']);
    }

    public function collection()
    {
        $statuses = Status::with('author.title')
        ->join('followers','followers.user_id','=','statuses.user_id')
        ->where('followers.follower_id','=',Auth::id())
        ->isPublic()
        ->ordered()
        ->select('statuses.*')
        ->paginate(config('preference.statuses_per_page'));
        return view('statuses.index', compact('statuses'))->with(['status_tab'=>'follow']);
    }
}
