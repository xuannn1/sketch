<h2 class="margin5">
    <span type="button" class="{{$user->isOnline()? '' : 'offline' }}">
      <i class="fas fa-user{{$user->admin? '-secret' : '' }}"></i>
    </span>
    <!-- @if($user->isOnline())
    <span class="badge">在线</span>
    @endif -->
    {{ $user->name }}
</h2>

@if ((Auth::check())&&($user->id != Auth::user()->id))
<span class="sosad-button-tag">
  @include('users._follow_button')
</span>
@endif


<div class="row text-center stats">
    <span><a href="{{route('users.followings', $user->id)}}" class="sosad-button-edit">关注：{{ $user->followings()->count() }}</a></span>
    <span class="sosad-button-edit grayout">|</span>
    <span><a href="{{route('users.followers', $user->id)}}" class="sosad-button-edit">粉丝：{{ $user->followers()->count() }}</a></span>
</div>

@if((Auth::check())&&(Auth::user()->admin))
@include('admin._modify_user')
@endif
<div class="stats smaller-10">
  <span class="stats-item">
    <span>等级</span><span>{{ $user->user_level }}</span>
  </span>

  <span class="stats-item">
    <span>盐度</span><span>{{ $user->experience_points }}</span>
  </span>

  <span class="stats-item">
    <span>积分</span><span>{{ $user->jifen }}</span>
  </span>

  <span class="stats-item">
    <span>剩饭</span><span>{{ $user->shengfan }}</span>
  </span>

  <span class="stats-item">
    <span>咸鱼</span><span>{{ $user->xianyu }}</span>
  </span>

  <span class="stats-item">
    <span>丧点</span><span>{{ $user->sangdian }}</span>
  </span>
</div>

<div class="stats smaller-20 well">
  <span class="stats-item">
    <span>连续签到</span><span>{{ $user->continued_qiandao }}天</span>
  </span>

  <span class="stats-item">
    <span>最多签到</span><span>{{ $user->maximum_qiandao }}天</span>
  </span>

  <span class="stats-item">
    <span>最后签到</span><span>{{ Carbon\Carbon::parse($user->lastrewarded_at)->diffForHumans() }}</span>
  </span>
</div>


<br>
<br>
<div class="thread-vote smaller-10">
  <span class="">
    <a type="button" class="sosad-button-post" href="{{ route('questions.create', $user) }}">
      <i class="fas fa-question"></i>
      问题箱
    </a>
  </span>
  &nbsp;
  <span class="">
    @include('users._message_button')
  </span>
  <!-- &nbsp;
  @if ((Auth::check())&&($user->id != Auth::user()->id))
  <span class="sosad-button-post">
    @include('users._follow_button')
  </span>
  @endif -->
  <!-- &nbsp;
  <span class="sosad-button-tag">
    <i class="fas fa-calendar-check btn-xs"></i>
  </span> -->
</div>
<br>
<br>


<div class = "post-body">
  <p>
    {!! Helper::wrapParagraphs($user->introduction) !!}
  </p>
</div>
<br>
