@foreach($threads as $thread)
<div class="">
    <a href="{{route('thread.show_profile', $thread->id)}}">{{$thread->title}}</a>&nbsp;
    <span class="grayout">{{$thread->brief}}</span>
</div>
@endforeach
