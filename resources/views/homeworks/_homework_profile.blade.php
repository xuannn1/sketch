<article class="{{ 'homework'.$homework->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <a href="{{ route('thread.show', $homework->thread->id) }}">{{ $homework->thread->title }}</a>
            <span class = "pull-right">
                <a href="{{ route('user.show', $homework->thread->user_id) }}">{{ $homework->thread->creator->name }}</a>
            </span>
        </div>
        <div class="col-xs-12 h6 ">
            一共{{ $homework->registerhomeworks->count() }}人报名：
            @foreach($homework->registerhomeworks as $registration)
            <span><a href="{{ route('user.show', $registration->user_id) }}">{{ $registration->student->name }}</a></span>{{ $registration->majia ? '（'.$registration->majia.'）':''}}，
            @endforeach
        </div>
        <div class="col-xs-12 h6 ">
            @foreach($homework->registerhomeworks as $registration)
            @if($registration->thread->id>0)
            <span><a href="{{ route('user.show', $registration->user_id) }}">{{ $registration->student->name }}</a></span>：<span><a href="{{ route('thread.show', $registration->thread->id) }}">{{ $registration->thread->title }}</a></span>：
            @foreach($registration->thread->posts as $post)
            @if(($post->id>0)&&($post->user_id!=$registration->user_id))
            <span><a href="{{ route('thread.showpost', $post->id) }}">{{ $post->owner->name }}{{'('.$post->up_voted.')'}}</a></span>，
            @endif
            @endforeach
            <br>
            @endif
            @endforeach
        </div>
        @if($homework->active)
        <div class="col-xs-12 h5">
            <a class="btn btn-xs btn-primary sosad-button" href="{{ route('homework.sendreminderform', $homework->id) }}">发送作业通知</a>
            <a class="btn btn-xs btn-success sosad-button" href="{{ route('homework.rewardsform', $homework->id) }}">作业奖惩</a>
            <a class="pull-right btn btn-xs btn-danger sosad-button" href="{{ route('homework.deactivate', $homework->id) }}">终止作业</a>
        </div>
        @endif
    </div>
    <hr>
</article>
