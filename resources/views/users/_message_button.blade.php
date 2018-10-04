@if ((Auth::check())&&($user->id != Auth::id())&&(Auth::user()->user_level>=2))
<a class="sosad-button-post {{'message'.$user->id}}" href="{{ route('messages.create', $user) }}">
  <i class="fas fa-envelope"></i>
  发私信
</a>
@endif
