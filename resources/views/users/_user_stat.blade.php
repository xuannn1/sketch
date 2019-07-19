@include('users._user_name')
<div class="row h4 text-center stat">
    <span><a href="{{route('user.followings', $user->id)}}">关注：{{ $info->following_count }}</a></span>
    &nbsp;&nbsp;
    &nbsp;&nbsp;
    <span><a href="{{route('user.followers', $user->id)}}">粉丝：{{ $info->follower_count }}</a></span>
</div>
<div class="stats h4">
    <span>盐粒：{{ $info->salt }}</span>&nbsp;&nbsp;<br>
    <span>咸鱼：{{ $info->fish }}</span>&nbsp;&nbsp;
    <span>火腿：{{ $info->ham }}</span>&nbsp;&nbsp;<br>
    <span>连续签到：{{ $info->qiandao_continued }}天</span>&nbsp;&nbsp;
    <span>最多连续签到：{{ $info->qiandao_max }}天</span>&nbsp;&nbsp;<br>
    <span>总签到：{{ $info->qiandao_all }}天</span>&nbsp;&nbsp;
    <span>最后签到时间：{{ $user->qiandao_at->diffForHumans() }}</span>
</div>
<br>
<div class="h5 text-center">
    <span>简介：{{ $info->brief_intro }}</span>
</div>
<br>
