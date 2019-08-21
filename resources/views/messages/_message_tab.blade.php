<ul class="nav nav-tabs">
    <li role="presentation" class="{{ $show_message_tab==='activities'? 'active':'' }}"><a href="{{ route('activity.index') }}">站内提醒<span class="badge badge-tag">{{ $activity_reminders>0?$activity_reminders:'' }}</span></a></li>
    <li role="presentation" class="{{ $show_message_tab==='messages'? 'active':''}}"><a href="{{ route('message.index') }}">收件箱<span class="badge badge-tag">{{ $messagebox_reminders>0?$messagebox_reminders:'' }}</span></a></li>
    <li role="presentation" class="{{ $show_message_tab==='sent'? 'active':'' }}"><a href="{{ route('message.sent') }}">发件箱</a></li>
    <li role="presentation" class="pull-right"><a href="{{route('message.clearupdates')}}" class="btn btn-md btn-primary sosad-button">标为已读</a></li>
</ul>
