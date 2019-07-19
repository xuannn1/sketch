<ul class="nav nav-tabs">
    <li role="presentation" class="{{ $show_following_tab==='following'? 'active':'' }}"><a href="{{ route('user.show', $user->id) }}">关注的人</a></li>
    <li role="presentation" class="{{ $show_user_tab==='status'? 'active':'' }} pull-right"><a href="{{ route('user.show_statuses', $user->id) }}">动态</a></li>
</ul>
