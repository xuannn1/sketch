@foreach($homeworks as $homework)
<article class="{{ 'homework'.$homework->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <a href="{{ route('thread.show', $homework->thread->id) }}">{{ $homework->thread->title }}</a>
            <span class = "pull-right">
                <a href="{{ route('user.show', $homework->thread->user_id) }}">{{ $homework->thread->creator->name }}</a>
            </span>
        </div>
        <div class="col-xs-12 h6 ">
            一共{{ $homework->registerhomeworks->count() }}人报名,
            @foreach($homework->registerhomeworks as $registration)
            <span><a href="{{ route('user.show', $registration->user_id) }}">{{ $registration->student->name }}</a></span>{{ $registration->majia ? '（'.$registration->majia.'）':''}}，
            @endforeach
        </div>
        <div class="col-xs-12 h5">
            <a class="btn btn-xs btn-success pull-right sosad-button" href="{{route('homework.show',$homework)}}">作业详情</a>
        </div>
    </div>
    <hr>
</article>
@endforeach
