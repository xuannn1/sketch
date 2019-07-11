@include('users._user_name')
<div class="row h4 text-center stat">
    <span><a href="{{route('users.followings', $user->id)}}">关注：{{ $info->following_count }}</a></span>
    &nbsp;&nbsp;
    @if ((Auth::check())&&($user->id != Auth::id()))
    <button type="button" class="btn btn-lg btn-primary sosad-button {{'follow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? 'hidden':''}}" onclick="follow({{$user->id}})">关注</button>
    <button type="button" class="btn btn-lg btn-danger sosad-button {{'cancelfollow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? '':'hidden'}}" onclick="cancelfollow({{$user->id}})">取消关注</button>
    @endif
    &nbsp;&nbsp;
    <span><a href="{{route('users.followers', $user->id)}}">粉丝：{{ $info->follower_count }}</a></span>
</div>
<div class="stats h4">
    <span>盐度：{{ $info->exp }}</span>&nbsp;&nbsp;
    <span>积分：{{ $info->jifen }}</span>&nbsp;&nbsp;<br>
    <span>剩饭：{{ $info->shengfan }}</span>&nbsp;&nbsp;
    <span>咸鱼：{{ $info->xianyu }}</span>&nbsp;&nbsp;
    <span>丧点：{{ $info->sangdian }}</span>&nbsp;&nbsp;
</div>
<br>
@if($info->introduction)
<div class="h5 text-center">
    <span>{!! Helper::wrapParagraphs($info->introduction) !!}</span>
</div>
@endif
<br>
@if((Auth::check())&&(Auth::user()->isAdmin()))
<div class="text-center row">
    <div class="col col-xs-6">
        <a href="#"class="btn btn-lg btn-danger sosad-button admin-button">管理该用户</a>
    </div>
    <div class="col col-xs-6">
        <a href="{{route('administrationrecords', ['user_id'=>$user->id])}}"class="btn btn-lg btn-danger sosad-button admin-button">看{{$user->name}}的被管理记录</a>
    </div>
</div>
<br>
@endif
