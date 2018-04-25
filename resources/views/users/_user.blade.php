<h3>
    <span type="button" class="{{$user->admin? 'admin-symbol' : '' }}"><span class="glyphicon glyphicon-user"></span></span>
    {{ $user->name }}
</h3>
@if($user->isOnline())
<span class="badge newchapter-badge ">在线</span>
@endif
<div class="row h5 text-center stat">
    <span><a href="{{route('users.followings', $user->id)}}">关注：{{ $user->followings()->count() }}</a></span>
    @include('users._follow_button')
    @include('users._message_button')
    <span><a href="{{route('users.followers', $user->id)}}">粉丝：{{ $user->followers()->count() }}</a></span>
</div>
@if((Auth::check())&&(Auth::user()->admin))
@include('admin._modify_user')
@endif
<div class="stats">
    <span>等级：{{ $user->user_level }}</span>，
    <span>盐度：{{ $user->experience_points }}</span>，
    <span>积分：{{ $user->jifen }}</span>，
    <span>剩饭：{{ $user->shengfan }}</span>，
    <span>咸鱼：{{ $user->xianyu }}</span>，
    <span>丧点：{{ $user->sangdian }}</span>，
    <br>
    <span>连续签到：{{ $user->continued_qiandao }}天</span>，
    <span>最多签到：{{ $user->maximum_qiandao }}天</span>，
    <span>最后签到时间：{{ Carbon\Carbon::parse($user->lastrewarded_at)->diffForHumans() }}</span>
</div>
<br>
<div class = "main-text">
    {!! Helper::wrapParagraphs($user->introduction) !!}
</div>
