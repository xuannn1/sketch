<ul class="nav nav-tabs">
    <li role="presentation" class="{{ $show_user_tab==='book'? 'active':'' }}"><a href="{{ route('user.show', $user->id) }}">书籍</a></li>
    <li role="presentation" class="{{ $show_user_tab==='thread'? 'active':'' }}"><a href="{{ route('user.show_threads', $user->id) }}">主题</a></li>
    <li role="presentation" class="{{ $show_user_tab==='comment'? 'active':'' }} pull-right"><a href="{{ route('user.show_comments', $user->id) }}">回帖</a></li>
    <li role="presentation" class="{{ $show_user_tab==='status'? 'active':'' }} pull-right"><a href="{{ route('user.show_statuses', $user->id) }}">动态</a></li>
</ul>
