<h1>
    <span type="button" class="{{$user->isAdmin()? 'admin-symbol' : '' }}"><span class="glyphicon glyphicon-user"></span></span>
    {{ $user->name }}
</h1>
<div class="row h4 text-center stat">
    <span><a href="{{route('users.followings', $user->id)}}">关注：{{ $user->followings()->count() }}</a></span>
    &nbsp;&nbsp;
    &nbsp;&nbsp;
    <span><a href="{{route('users.followers', $user->id)}}">粉丝：{{ $user->followers()->count() }}</a></span>
</div>
<div class="stats h5">
    <span>等级：{{ $user->level }}</span>，
    <span>盐度：{{ $info->exp }}</span>，
    <span>积分：{{ $info->jifen }}</span>，
    <span>剩饭：{{ $info->shengfan }}</span>，
    <span>咸鱼：{{ $info->xianyu }}</span>，
    <span>丧点：{{ $info->sangdian }}</span>，
    <br>
    <span>连续签到：{{ $info->continued_qiandao }}天</span>，
    <span>最多签到：{{ $info->max_qiandao }}天</span>，
    <span>最后签到时间：{{ $user->qiandao_at->diffForHumans() }}</span>
</div>
<br>
