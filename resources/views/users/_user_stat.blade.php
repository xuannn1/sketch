@include('users._user_name')
<div class="row h4 text-center stat">
    <span><a href="{{route('users.followings', $user->id)}}">关注：{{ $info->following_count }}</a></span>
    &nbsp;&nbsp;
    &nbsp;&nbsp;
    <span><a href="{{route('users.followers', $user->id)}}">粉丝：{{ $info->follower_count }}</a></span>
</div>
<div class="stats h4">
    <span>盐度：{{ $info->exp }}</span>&nbsp;&nbsp;
    <span>积分：{{ $info->jifen }}</span>&nbsp;&nbsp;<br>
    <span>剩饭：{{ $info->shengfan }}</span>&nbsp;&nbsp;
    <span>咸鱼：{{ $info->xianyu }}</span>&nbsp;&nbsp;
    <span>丧点：{{ $info->sangdian }}</span>&nbsp;&nbsp;<br>
    <span>连续签到：{{ $info->continued_qiandao }}天</span>&nbsp;&nbsp;
    <span>最多签到：{{ $info->max_qiandao }}天</span>&nbsp;&nbsp;<br>
    <span>最后签到时间：{{ $user->qiandao_at->diffForHumans() }}</span>
</div>
<br>
<div class="h5 text-center">
    <span>简介：{{ $info->brief_intro }}</span>
</div>
<br>
