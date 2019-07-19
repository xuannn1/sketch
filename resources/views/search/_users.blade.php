@foreach($users as $user)
<a href="{{route('user.show', $user->id)}}">{{$user->name}}</a>
@endforeach
