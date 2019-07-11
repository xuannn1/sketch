<h1><span class="glyphicon glyphicon-user {{$user->isAdmin()? 'admin-symbol' : '' }}"></span>{{ $user->name }}</h1>
<div class="font-2">
    <span>Lv.{{ $user->level }}</span>
    @if($user->title&&$user->title->name)
    <span>{{ $user->title->name }}</span>
    @endif
</div>
