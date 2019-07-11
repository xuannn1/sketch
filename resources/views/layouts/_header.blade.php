<header class="navbar navbar-inverse">
    <div class="container">
        <div class="col-md-offset-1 col-md-10">
            <a href="{{ route('home') }}" id="logo">废文网</a>
            <input type="hidden" id="baseurl" name="baseurl" value= "{{route('home')}}"/>
            <nav>
                @if(Auth::check())
                <?php $Auser = Auth::user() ?>
                <ul class="nav navbar-nav navbar-right text-right">
                    @if($Auser->isAdmin())
                    <li><a href="{{ route('admin.index') }}" class="admin-symbol">管理员</a></li>
                    @endif
                    @if ($Auser->qiandao_at <= Carbon\Carbon::today()->subHours(2))
                        <li><a href="{{ route('qiandao') }}" style="color:#d66666">我要签到</a></li>
                    @else
                        @if($Auser->quiz_level==0)
                        <li><a href="{{ route('quiz.taketest') }}" style="color:#e3a300">我要答题</a></li>
                        @endif
                    @endif
                    <li><a href="{{ route('statuses.collections') }}">动态</a></li>
                    <li><a href="{{ route('books.index') }}">文库</a></li>
                    <li><a href="{{ route('threads.thread_index') }}">论坛</a></li>
                    <li><a href="{{ route('collections.books') }}">收藏<span class="badge">{{ $Auser->unread_updates!=0? $Auser->unread_updates :''}}</span></a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="{{Auth::user()->unread_reminders>0? 'blink_me reminder-sign':''}}">
                            <span class="glyphicon glyphicon-bell {{Auth::user()->unread_reminders>0? :'hidden'}}"></span>{{ Auth::user()->name }} <b class="caret"></b></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('user.center') }}">个人中心</a></li>
                            <li><a href="{{ route('messages.unread') }}">消息中心<span class="badge">{{ Auth::user()->unread_reminders>0 ? Auth::user()->unread_reminders:''}}</span></a></li>
                            <li><a href="{{ route('linkedaccounts.index') }}">切换账户</a></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">退出</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @else
                <ul class="nav navbar-nav navbar-right text-right">
                    <li><a href="{{ route('statuses.index') }}">动态</a></li>
                    <li><a href="{{ route('books.index') }}">文库</a></li>
                    <li><a href="{{ route('threads.index') }}">论坛</a></li>
                    <li><a href="{{ route('register') }}">注册</a></li>
                    <li><a href="{{ route('login') }}">登录</a></li>
                </ul>
                @endif
            </nav>
        </div>
    </div>
</header>
