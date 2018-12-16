@foreach($threads as $thread)
<div class="">
    {{$thread->id}}-{{$thread->title}}
</div>
@endforeach
