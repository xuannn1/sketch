@foreach($users as $user)
<article class="{{ 'user'.$user->id }}">
    <div class="container-fluid user">
        <div class="">
            <span class="">
                <i class="fa fa-user{{$user->admin? '-secret admin-symbol' : '' }}"></i>
                <a href="{{ route('user.show', $user->id) }}"><b>{{ $user->name }}</b></a>
            </span>&nbsp;&nbsp;
            <span class="smaller-10 grayout">
                <!-- {{$user->introduction}} -->
                {!! Helper::trimtext($user->introduction,15) !!}
            </span>
            <span class="pull-right">
                @include('users._follow_button')
            </span>
        </div>
    </div>
</article>
@endforeach
