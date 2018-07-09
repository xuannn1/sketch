@if ((Auth::check())&&($user->id != Auth::id())&&(Auth::user()->user_level>=2))
<a class="btn btn-xs btn-primary sosad-button {{'message'.$user->id}}" href="{{ route('messages.create', $user) }}">发私信</a>
@endif
