@extends('layouts.default')
@section('title', "帮助")

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>帮助中心</h1>
            </div>
        </div>
        @foreach(config('faq') as $key1=>$value1)
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="font-2">【{{$value1['title']}}】</span>
            </div>
            <div class="panel-body">
                @foreach($value1['children'] as $key2=>$value2)
                <div class="">
                    <div >
                        <a type="button" data-toggle="collapse" data-target="#help{{$key1}}-{{$key2}}" style="cursor: pointer;" class="font-4">
                            &nbsp;{{$key2}}&nbsp;{{$value2}}</a>
                        @if(Auth::check()&&Auth::user()->isAdmin())
                        <span>
                            <a href="{{route('faq.create',['key'=>$key1.'-'.$key2])}}" class="pull-right smaller-20 admin-symbol">添加<i class="fa fa-plus" aria-hidden="true"></i></a>
                        </span>
                        @endif
                    </div>
                    <div class="collapse" id = "help{{$key1}}-{{$key2}}">
                        <?php $QnAs = $faqs[$key1.'-'.$key2]; ?>
                        @foreach($QnAs as $i => $QnA)
                        <div class="main-text post-reply">
                            <a type="button" data-toggle="collapse" data-target="#helpQnA{{$QnA->id}}" style="cursor: pointer;" class="font-5">Q：{{$QnA->question}}</a>
                            @if(Auth::check()&&Auth::user()->isAdmin())
                            <span>
                                &nbsp;&nbsp;&nbsp;<a href="{{route('faq.edit', $QnA->id)}}" class="smaller-20 admin-symbol">修改本条<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </span>
                            @endif
                        </div>
                        <div class="collapse main-text post-reply font-5 grayout" id="helpQnA{{$QnA->id}}">
                            A：{!! StringProcess::wrapSpan($QnA->answer) !!}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        <div class="panel panel-default" id="help-constants">
            <div class="panel-heading">
                <span class="font-2">【当前参数】</span>
            </div>
            <div class="panel-body">
                <div class="">
                    <div class="">
                        <a type="button" id="help-constants-1" data-toggle="collapse" data-target="#help-constants-level" style="cursor: pointer;" class="font-4">
                            &nbsp;1 升级需求和等级权限：</a>
                    </div>
                    <div class="collapse font-6" id="help-constants-level">
                        <div class="">
                            @foreach(config('level.level_up') as $level=>$level_req)
                            <div class="">
                                <span>&nbsp;&nbsp;{{$level}}级需要：</span>
                                @foreach(config('level.values') as $key=>$value)
                                @if(array_key_exists($key,$level_req))
                                <span class="grayout">{{$value}}:{{$level_req[$key]}}，</span>
                                @endif
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        <div class="font-6">
                            注：升级检验只发生在每日签到时，每天最多升一级。
                        </div>
                    </div>
                </div>
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-rewards" style="cursor: pointer;" class="font-4">
                        &nbsp;2 虚拟物奖励数据：</a>
                    <div class="collapse indentation main-text font-6 grayout" id="help-constants-rewards">
                        <div class="">
                            <p>动态奖励：1盐粒</p>
                            <p>普通回帖奖励：1咸鱼</p>
                            <p>长评奖励（回帖超过200字）：5盐粒3咸鱼1火腿</p>
                            <p>新章节率先回帖奖励：4盐粒2咸鱼</p>
                            <p>普通主题贴奖励：5盐粒2咸鱼</p>
                            <p>普通书籍奖励：10盐粒5咸鱼2火腿</p>
                            <p>短章节奖励（更新字数小于1000字）：5盐粒1咸鱼</p>
                            <p>普通章节奖励（更新字数大于1000字）：10盐粒1咸鱼1火腿</p>
                            <p>多人赞赏奖励：5盐粒1咸鱼1火腿</p>
                            <p>首次答题奖励：答题等级*5盐粒，答题等级*1咸鱼</p>
                            <p>普通签到奖励：5盐粒，1咸鱼</p>
                            <p>签到特别奖励：盐粒若干，咸鱼若干，具体数值和连续签到天数有关</p>
                            <p>（删除回帖/书籍时，会折扣发布时奖励的虚拟物。）</p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-setting" style="cursor: pointer;" class="font-4">
                        &nbsp;3 系统设置：</a>
                    <div class="collapse indentation main-text font-6 grayout" id="help-constants-setting">
                        <p>a)章节更新必须达到这个字数才能进入排名榜:{{config('constants.update_min')}}字</p>
                        <p>b)“长评”必须达到该字数:{{config('constants.longcomment_length')}}字</p>
                        <p>c)一个月能修改多少次邮箱:{{config('constants.monthly_email_resets')}}次</p>
                    </div>
                </div>
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-data-yesterday" style="cursor: pointer;" class="font-4">
                        &nbsp;4 昨日数据：</a>
                    <div class="collapse indentation main-text font-6 grayout" id="help-constants-data-yesterday">
                        <div class="">
                            <p>(统计数据更新于：{{$webstat->created_at->DiffForHumans()}})</p>
                            <p>昨日签到人数：{{$webstat->qiandaos}}</p>
                            <p>昨日新增回帖：{{$webstat->posts}}</p>
                            <p>昨日新增章节：{{$webstat->chapters}}</p>
                            <p>昨日新注册用户：{{$webstat->new_users}}</p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-data-now" style="cursor: pointer;" class="font-4">
                        &nbsp;5 当前数据：</a>
                    <div class="collapse indentation main-text font-6 grayout" id="help-constants-data-now">
                        <div class="">
                            <p>当前在线人数：数据调整中，暂不显示</p>
                        </div>
                    </div>
                </div>
                @if(Auth::check())
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-forbiddenwords-public" style="cursor: pointer;" class="font-4">
                        &nbsp;6 出现在标题/简介/章节名中会被隐藏的词汇（用‘|’隔开）：</a>
                    <div class="collapse" id="help-constants-forbiddenwords-public">
                        <div class="">
                            <img src="/img/forbidden_words.png" alt="forbidden_words">
                        </div>
                    </div>
                </div>
                <div class="">
                    <a type="button" id="help-constants-6" data-toggle="collapse" data-target="#help-constants-forbiddenwords-title" style="cursor: pointer;" class="font-4">
                        &nbsp;7 出现在书名中会被隐藏的词汇（用‘|’隔开）：</a>
                    <div class="collapse" id="help-constants-forbiddenwords-title">
                        <div class="">
                            {{ config('forbiddenwords.not_in_title') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
