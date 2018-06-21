@foreach($system_reminders as $system_reminder)
<article class="">
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <span id="system_reminder{{$system_reminder->id}}">
                {{ Carbon\Carbon::parse($system_reminder->created_at)->diffForHumans() }}提问：<a href="{{ route('questions.index', Auth::id()) }}">{!! Helper::trimtext($system_reminder->question_body,40) !!}</a>
            </span>
        </div>
    </div>
</article>
@endforeach
