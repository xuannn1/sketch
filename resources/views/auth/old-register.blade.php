@extends('layouts.default')
@section('title', '注册')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h1>注册</h1>
                <h5 class="text-center">成为废文网的一条咸鱼吧～</h5>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">用户名（笔名）：</label>
                        <h6 class="grayout">（用户名注册后，暂时无法更改哦。）</h6>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <h6 class="grayout">（请输入你的可用邮箱，便于未来找回密码。）</h6>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="email_confirmation">确认邮箱：</label>
                        <input type="text" name="email_confirmation" class="form-control" value="{{ old('email_confirmation') }}">
                        <h6><span style="color:#d66666">友情提醒，请【仔细】检查邮箱输入情况，确认邮箱无误！</span>输入错误的邮箱将无法激活自己的账户，也无法找回自己的账户。<br>虽然很多用户都说自己的输入“绝对没有问题”，然而实践表明，几乎——几乎——所有的登陆问题都是邮箱输入错误导致……<br>为了确保验证邮件正常送达，请务必使用个人<code>目前常用、可用的</code>邮箱地址。</h6>
                    </div>

                    <div class="form-group">
                        <label for="password">密码：</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">确认密码：</label>
                        <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
                    </div>
                    <div class="form-group">
                        <label for="invitation_token">邀请码：</label>
                        <h6 class="grayout">（ 邀请码详情查看微博号“废文网大内总管”，或查看站内 <a href="{{ route('thread.show', 2615) }}">公用邀请码楼</a> ）</h6>
                        <h6 class="grayout">（ 公共邀请码最近一次“刷新”大约发生在
                            {!! Carbon::parse(ConstantObjects::system_variable()->token_refreshed_at)->diffForHumans() !!} ）
                        </h6>
                        <input type="text" name="invitation_token" class="form-control" value="{{ old('invitation_token') }}">
                    </div>
                    <div class="panel panel-default text-center">
                        <div class="panel-title">
                            <h4>注册协议</h4>
                        </div>
                        <div >
                            <p>丧病之家，你的精神墓园</p>
                            <p>比欲哭无泪更加down，不抑郁不要钱</p>
                            <p>本站<u><em><b>禁抄袭，禁人身攻击，禁人肉，禁恋童</b></em></u></p>
                            <p>请<u><em><b>不要发布侵犯他人版权的文字</b></em></u></p>
                            <p>请确保你已<u><em><b>年满<span style="color:#d66666">十八</span>岁</b></em></u></p>
                            <p>祝你玩得愉快</p>
                            <br>
                        </div>
                        <div class="panel-footer text-center">
                            <div class="text-center chapter">
                                <label for="promise">注册担保：</label>
                                <h6 class="grayout">请手工输入下面这句红色的话：</h6>
                                <h6 class="" style="color:#f44248"><em>{{ config('preference.register_promise') }}</em></h6>
                                <input type="text" name="promise" class="form-control" value="{{ old('promise') }}">
                            </div>
                        </div>
                        <div class="panel-footer text-center h6">
                            <div class="">
                                <input type="checkbox" name="have_read_policy1" value=true>
                                <span>我知道在所有页面的右下角的《帮助》页面可以找到各种使用疑难解答</span>&nbsp;<u><a href="{{'help'}}">帮助页面</a></u>
                            </div>
                            <div class="">
                                <input type="checkbox" name="have_read_policy2" value=true>
                                <span>我知道在所有页面的右下角的《关于》页面可以找到本站介绍和《版规》入口</span>&nbsp;<u><a href="{{'about'}}">关于页面</a></u>
                            </div>
                            <div class="">
                                <input type="checkbox" name="have_read_policy3" value=true>
                                <span>我已阅读《版规》中约定的社区公约，同意遵守版规</span>&nbsp;<u><a href="{{ route('thread.show', 136) }}">版规详情</a></u>
                            </div>
                            <div class="">
                                <input type="checkbox" name="have_read_policy4" value=true>
                                <span>我已<span style="color:#d66666">年满十八周岁</span>，神智健全清醒，保证为自己的言行负责。</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-md btn-danger sosad-button">一键注册</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
