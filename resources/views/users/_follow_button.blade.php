@if ((Auth::check())&&($user->id !== Auth::user()->id))
<button type="button" class="btn btn-xs btn-primary sosad-button {{'follow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? 'hidden':''}}" onclick="follow({{$user->id}})">关注</button>
<button type="button" class="btn btn-xs btn-danger sosad-button {{'cancelfollow'.$user->id}} {{Auth::user()->isFollowing($user->id) ? '':'hidden'}}" onclick="cancelfollow({{$user->id}})">取消关注</button>
@endif
