@if ((Auth::check())&&($user->id != Auth::user()->id))
<button type="button" class="sosad-button-ghost btn-xs {{'follow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? 'hidden':''}}" onclick="follow({{$user->id}})">
    <i class="fa fa-plus"></i>
    关注
</button>
<button type="button" class="sosad-button-ghost btn-xs {{'cancelfollow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? '':'hidden'}}" onclick="cancelfollow({{$user->id}})">
    <i class="fa fa-minus"></i>
    取消关注
</button>
@endif
