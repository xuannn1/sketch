@foreach($users as $user)
<article class="{{ 'user'.$user->id }}">
    <div class="container-fluid">
        <div class="h5">
            <span>lv.{{ $user->level }}</span>
            @if($user->title&&$user->title->name)
            <span class="maintitle title-{{$user->title->style_id}}">{{ $user->title->name }}</span>
            @endif
            <span class="glyphicon glyphicon-user {{$user->admin? 'admin-symbol' : '' }}"><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></span>&nbsp;&nbsp;
            <span class="smaller-10">{{ $user->info->brief_intro }}</span>
        </div>
    </div>
    <hr class="brief-2">
</article>
@endforeach
