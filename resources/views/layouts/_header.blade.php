<header class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="col-md-offset-1 col-md-10">
      <a href="{{ route('home') }}" id="logo">
          <img src="/img/sosad-logo.png" alt="废文网">
          <!-- 废文网 -->
      </a>
      <input type="hidden" id="baseurl" name="baseurl" value= "{{route('home')}}"/>
      <nav>
          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                  <span class="sr-only">切换导航</span>
                  <i class="fa fa-bars"></i>
                  @if (Auth::check())
                    <span class="badge {{Auth::user()->unread_reminders>0? 'blink_me':'hidden'}}">
                      {{ auth()->user()->unreadmessages()>0 ? auth()->user()->unreadmessages():''}}
                    </span>
                  @endif
              </button>

          </div>
          <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="nav navbar-nav navbar-right text-right">
                @if(Auth::check())
                <li>
                 <div class="search-container" id="search-container">
                     <form method="GET" action="{{ route('search') }}" id="search_form">
                         <!-- <div class="search-input"> -->
                             <select name="search_options" form="search_form" onchange=searchBarAdjust()>
                             <option value ="threads">标题</option>
                             <option value ="users">用户</option>
                             <option value ="tongren_yuanzhu" >原著</option>
                              </select>
                              <input type="textarea" placeholder="搜索..." name="search" id="search_keyword">
                              <input type="textarea" placeholder="+CP简称" name="tongren_cp" id="tongren_cp_name" style="display:none">
                         <!-- </div> -->
                         <button class="search-submit" type="submit" value="">
                         <i class="fa fa-search"></i>
                         </button>
                     </form>
                   </div>
                 </li>
                 @endif
               @if (Auth::check()&&(Auth::user()->admin))
                  <li><a href="{{ route('admin.index') }}" class="admin-symbol">管理员</a></li>
               @endif

               @if (Auth::check()&&(Auth::user()->lastrewarded_at <= Carbon\Carbon::today()->subHours(2)->toDateTimeString()))
                <li><a href="{{ route('qiandao') }}" style="color:var(--link-hover-color)">
                  <i class="far fa-calendar-check"></i>
                  签到
                </a></li>
               @endif

                @if(Auth::check())
                    <li><a href="{{ route('statuses.collections') }}">
                        <i class="fa fa-heartbeat"></i>
                        动态</a></li>
                @else
                    <li><a href="{{ route('statuses.index') }}">
                      <i class="fa fa-heartbeat"></i>
                      动态
                    </a></li>
                @endif

               <li><a href="{{ route('books.index') }}">
                   <i class="fa fa-book"></i>
                   文库</a></li>
               <li>
                 <a href="{{ route('threads.index') }}">
                     <i class="fa fa-comment"></i>
                   论坛</a>
               </li>
              @if (Auth::check())
                <li><a href="{{ route('collections.books') }}">
                    <i class="fa fa-star"></i>
                    收藏<span class="badge">{{ Auth::user()->unreadupdates()!=0? Auth::user()->unreadupdates() :''}}</span></a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="{{auth()->user()->unread_reminders>0? 'blink_me reminder-sign':''}}">
                    <span class="glyphicon glyphicon-bell {{auth()->user()->unreadmessages()>0? :'hidden'}}"></span>
                    <i class="fa fa-user-circle"></i>
                    {{ auth()->user()->name }} <b class="caret"></b></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="{{ route('user.show', auth()->user()->id) }}">个人主页</a></li>
                    <li><a href="{{ route('users.edit') }}">编辑资料</a></li>
                    <li><a href="{{ route('book.create') }}">我要发文</a></li>
                    <li><a href="{{ route('messages.unread') }}">消息中心
                      <span class="badge">
                        {{ auth()->user()->unreadmessages()!=0? auth()->user()->unreadmessages() :''}}
                      </span></a>
                    </li>
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
                <li><a href="{{ route('register') }}">
                    <i class="fa fa-edit"></i>
                    注册</a></li>
                <li><a href="{{ route('login') }}">
                    <i class="fa fa-key"></i>
                    登录</a></li>
              @endif
              <!-- <li>
                <a href="#" onclick="switchTheme()">
                  <i class="far fa-lightbulb"></i> 切换主题
                </a>
              </li> -->
            </ul>
          <div>
      </nav>
  </div>
  </div>

</header>

<div class="theme-changer">
  <label id="theme-changer">
    <i class="fas fa-tshirt"></i>
     <select name="theme" id="theme">
       <option value="default">白天</option>
       <option value="dark">黑夜</option>
       <option value="solarized">黄昏</option>
       <option value="sosad">阴雨</option>
     </select>
   </label>
</div>

<div class="hidden alert" id="ajax-message">
</div>
