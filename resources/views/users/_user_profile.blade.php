<br>
<div class="font-2">
    <span class="glyphicon glyphicon-user {{$user->role.'-symbol'}}"></span>{{ $user->name }}
</div>
<div class="font-5">
    <span >Lv.{{ $user->level }}</span>
    @if($user->title&&$user->title->name)
        <span class="maintitle title-{{$user->title->style_id}}">{{ $user->title->name }}</span>
    @endif
    @if($user->activated)
    <span class="badge">已激活</span>
    @else
    <span class="badge">未激活</span>
    @endif
    @if($user->no_posting)
    <span class="badge bianyuan-tag badge-tag">禁言中</span>
    @endif
    @if($user->no_logging)
    <span class="badge bianyuan-tag badge-tag">封禁中</span>
    @endif
    @if($user->isOnline())
    <span class="badge">在线</span>
    @endif
    @if($user->no_ads)
    <span class="badge">免广告</span>
    @endif
</div>
<div class="text-center font-4">
    <span><a href="{{route('user.followings', $user->id)}}">关注{{ $info->following_count }}</a></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <span><a href="{{route('user.followers', $user->id)}}">粉丝{{ $info->follower_count }}</a></span>
</div>

@if(Auth::check())
<div class="text-center">
    @if(Auth::user()->isAdmin())
    <a href="{{route('admin.userform', $user->id)}}"class="btn btn-md btn-danger sosad-button admin-button">管理该用户</a>&nbsp;&nbsp;&nbsp;
    @endif
    @if(Auth::id()!=$user->id)
    <a href="{{route('message.dialogue', $user->id)}}" class="btn btn-md btn-primary sosad-button-control" >私信</a>&nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-md btn-primary sosad-button-control {{'follow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? 'hidden':''}}" onclick="follow({{$user->id}})">关注</button>
    <button type="button" class="btn btn-md btn-danger sosad-button {{'cancelfollow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? '':'hidden'}}" onclick="cancelfollow({{$user->id}})">取消关注</button>&nbsp;&nbsp;&nbsp;
    @endif
    <a href="{{$info->default_box_id===0?'#':route('thread.show', $info->default_box_id)}}" class="btn btn-md btn-primary sosad-button-control" {{$info->default_box_id===0? 'disabled':''}}>问题箱</a>
    @if(Auth::user()->isAdmin())
    &nbsp;&nbsp;&nbsp;<a href="{{route('administrationrecords', ['user_id'=>$user->id])}}"class="btn btn-md btn-danger sosad-button admin-button">被管理记录</a>
    @endif
</div>
@endif
<div class="text-center stat">

    <div class="font-5">
        <span>盐粒：{{ $info->salt }}</span>&nbsp;&nbsp;&nbsp;
        <span>咸鱼：{{ $info->fish }}</span>&nbsp;&nbsp;&nbsp;
        <span>火腿：{{ $info->ham }}</span>
    </div>
    <div class="font-5">
        <span>答题等级：{{ $user->quiz_level }}</span>&nbsp;&nbsp;&nbsp;
        <span>最多连续签到：{{ $info->qiandao_max }}天</span>
    </div>
    @if(Auth::check()&&(Auth::user()->isAdmin()||Auth::id()===$user->id))
        <div class="font-5">
            <span>连续签到：{{ $info->qiandao_continued }}天</span>&nbsp;&nbsp;&nbsp;
            @if($info->qiandao_reward_limit>0)
            <span>补签卡：{{ $info->qiandao_reward_limit }}张</span>&nbsp;&nbsp;&nbsp;
            @endif
            @if($info->qiandao_continued==1&&$info->qiandao_last>1&&$info->qiandao_reward_limit>0)
            <a href="{{route('donation.mydonations')}}">前去补签</a>
            @endif
            <span>总签到：{{ $info->qiandao_all }}天</span>&nbsp;&nbsp;&nbsp;
            <span>最新签到：{{ $user->qiandao_at? $user->qiandao_at->diffForHumans():'' }}</span>
        </div>
        <div class="font-5">
            <span>注册时间：{{ $user->created_at? $user->created_at->diffForHumans():'无记录' }}</span>&nbsp;&nbsp;&nbsp;
            <span>最后在线：{{ $info->online_status&&$info->online_status->online_at? $info->online_status->online_at->diffForHumans():'无记录' }}</span>&nbsp;&nbsp;&nbsp;
            @if($info->donation_level>0)
            <span>赞助情况：{{ array_key_exists($info->donation_level, config('donation'))? config('donation')[$info->donation_level]['title']:'暂无'}}</span>
            @endif
        </div>

        <div class="font-5">
            @if($info->invitor_id>0)
            <span><a href="{{route('user.show', $info->invitor_id)}}">邀请人:UID{{ $info->invitor_id }}</a></span>&nbsp;&nbsp;&nbsp;
            @endif
            @if($info->token_limit>0)
            <span>邀请额度：{{ $info->token_limit }}</span>&nbsp;&nbsp;&nbsp;
            @endif
        </div>
    @endif
</div>

@if($intro&&$intro->body)
<div class="h5 text-center stat">
    <span>{!! StringProcess::wrapParagraphs($intro->body) !!}</span>
</div>
@endif
