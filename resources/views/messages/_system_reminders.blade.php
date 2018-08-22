@foreach($system_reminders as $system_reminder)
<article class="margin5">
    <div class="row">
        <div class="col-xs-12">
            <span id="system_reminder{{$system_reminder->id}}">
              <span class="grayout">
                {{ Carbon\Carbon::parse($system_reminder->created_at)->diffForHumans() }}有人提问：
              </span>
                <a href="{{ route('questions.index', Auth::id()) }}">{!! Helper::trimtext($system_reminder->question_body,40) !!}</a>
            </span>
        </div>
    </div>
</article>
@endforeach
