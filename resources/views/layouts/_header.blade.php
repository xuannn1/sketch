<header class="navbar navbar-inverse">
    <div class="container">
        <div class="col-md-offset-1 col-md-10">
            <a href="{{ route('home') }}" id="logo">废文网</a>
            <input type="hidden" id="baseurl" name="baseurl" value= "{{route('home')}}"/>
            <nav>
                <ul class="nav navbar-nav navbar-right text-right">
                    @if (Auth::check()&&(Auth::user()->admin))
                    <li><a href="{{ route('admin.index') }}" class="admin-symbol">管理员</a></li>
                    @endif
                    @if (Auth::check()&&(Auth::user()->lastrewarded_at <= Carbon\Carbon::today()->subHours(2)->toDateTimeString()))
                    <li><a href="{{ route('qiandao') }}" style="color:#d66666">我要签到</a></li>
                    @endif
                    @if(Auth::check())
                    <li><a href="{{ route('statuses.collections') }}">动态</a></li>
                    @else
                    <li><a href="{{ route('statuses.index') }}">动态</a></li>
                    @endif
                    <li><a href="{{ route('books.index') }}">文库</a></li>
                    <li><a href="{{ route('threads.index') }}">论坛</a></li>
                    @if (Auth::check())
                    <li><a href="{{ route('collections.books') }}">收藏<span class="badge">{{ Auth::user()->unreadupdates()!=0? Auth::user()->unreadupdates() :''}}</span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="{{Auth::user()->unread_reminders>0? 'blink_me reminder-sign':''}}">
                            <span class="glyphicon glyphicon-bell {{Auth::user()->unreadmessages()>0? :'hidden'}}"></span>{{ Auth::user()->name }} <b class="caret"></b></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('user.show', Auth::user()->id) }}">个人主页</a></li>
                            <li><a href="{{ route('users.edit') }}">编辑资料</a></li>
                            <li><a href="{{ route('book.create') }}">我要发文</a></li>
                            <li><a href="{{ route('messages.unread') }}">消息中心<span class="badge">{{ auth()->user()->unreadmessages()>0 ? auth()->user()->unreadmessages():''}}</span></a></li>
                            @foreach(Auth::user()->linkedaccounts() as $account)
                            <li><a href="{{ route('linkedaccounts.switch', $account->id) }}">切换：{{ $account->name }}</a></li>
                            @endforeach
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">退出</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li><a href="{{ route('register') }}">注册</a></li>
                    <li><a href="{{ route('login') }}">登录</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="hidden alert" id="ajax-message">
</div>
