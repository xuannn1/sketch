@foreach($users as $user)
<span><a href="{{route('user.show', $user->id)}}"><span class="glyphicon glyphicon-user {{$user->role.'-symbol'}}"></span>{{$user->name}}</a></span>&nbsp;&nbsp;

@endforeach
