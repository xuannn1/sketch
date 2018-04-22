@foreach($users as $user)
<article class="{{ 'user'.$user->id }}">
    <div class="container-fluid">
        <div class="h5">
            <span class="glyphicon glyphicon-user {{$user->admin? 'admin-symbol' : '' }}"><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></span>&nbsp;&nbsp;
            @if($user->isOnline())
            <span class="badge newchapter-badge ">在线</span>
            @endif
            <span class="smaller-10">{!! Helper::trimtext($user->introduction,15) !!}</span>
            <span class="pull-right">
                @include('users._follow_button')
            </span>
        </div>
    </div>
    <hr class="narrow">
</article>
@endforeach
