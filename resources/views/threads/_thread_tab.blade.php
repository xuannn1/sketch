<ul class="nav nav-tabs">
    <li role="presentation" class="{{ $threads_tab==='index'?'active':'' }}"><a href="{{ route('threads.thread_index') }}">讨论帖</a></li>
    <li role="presentation" class="{{ $threads_tab==='jinghua'?'active':'' }}"><a href="{{ route('threads.thread_jinghua') }}">精华</a></li>
    <li role="presentation" class="{{ $threads_tab==='box'?'active':'' }} pull-right"><a href="{{ route('channel.show', config('constants.box_channel_id')) }}">问题箱</a></li>
    <li role="presentation" class="{{ $threads_tab==='list'?'active':'' }} pull-right"><a href="{{ route('channel.show', config('constants.list_channel_id')) }}">清单</a></li>
</ul>
