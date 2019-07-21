<h2><span class="glyphicon glyphicon-user {{$user->role.'-symbol'}}"></span>{{ $user->name }}</h2>
<div class="font-4">
    <span>Lv.{{ $user->level }}</span>
    @if($user->title&&$user->title->name)
    <span>{{ $user->title->name }}</span>
    @endif
</div>
