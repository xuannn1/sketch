@extends('layouts.default')
@section('title', '邀请注册')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h1>邀请注册</h1>
                <h4>友情提醒，本页面含有IP访问频率限制，为了你的正常注册，注册时<code>请不要刷新或倒退</code>网页。</h4>
                @if(!$invitation_token->is_public)
                <h5>你使用了私人邀请码！你的邀请人是：<a href="{{route('user.show', $invitation_token->user_id)}}">{{$invitation_token->user->name}}</a></h5>
                <h5 style="color:#d66666">如果被邀请人严重违反版规，邀请人需负连带责任。</h5>
                @endif
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="invitation_token">邀请码：</label>
                        <input type="text" name="invitation_token" class="form-control hidden" value="{{ $invitation_token->token }}">
                        <input type="text" class="form-control" value="{{ $invitation_token->token }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="name">用户名（笔名）：</label>
                        <h6 class="grayout">（用户名注册后，暂时无法更改哦。）</h6>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <h6 class="grayout">（请输入你的可用邮箱，用于激活账户和未来找回密码。）</h6>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="email_confirmation">确认邮箱：</label>
                        <input type="text" name="email_confirmation" class="form-control" value="{{ old('email_confirmation') }}">
                        <h6>友情提醒，请<span style="color:#d66666">仔细检查邮箱</span>输入情况，确认邮箱无误。输入错误的邮箱将无法激活自己的账户，也无法找回自己的账户。<br>为了确保验证邮件正常送达，请务必使用个人<span style="color:#d66666">目前常用、可用的</span>邮箱地址。</h6>
                    </div>

                    <div class="form-group">
                        <label for="password">密码：</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                        <h6>(密码需包含至少一个大写字母，至少一个小写字母，至少一个数字，至少一个特殊字符)</h6>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">确认密码：</label>
                        <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
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
                                <textarea name="promise" data-provide="markdown" rows="3" class="form-control">{{ old('promise') }}</textarea>
                            </div>
                        </div>
                        <div class="panel-footer text-center h6">
                            <div class="">
                                <input type="checkbox" name="have_read_policy1" value=true>
                                <span>我知道可以左上角【搜索】关键词获取使用帮助</span>&nbsp;<u><a href="{{'help'}}">帮助页面</a></u>
                            </div>
                            <div class="">
                                <input type="checkbox" name="have_read_policy2" value=true>
                                <span>我已阅读《版规》中约定的社区公约，同意遵守版规</span>&nbsp;<u><a href="{{ route('thread.show', 136) }}">版规详情</a></u>
                            </div>
                            <div class="">
                                <input type="checkbox" name="have_read_policy3" value=true>
                                <span>我保证自己<span style="color:#d66666">年满十八周岁</span>，神智健全清醒，承诺为自己的言行负责。</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-4">
                            <div class="captcha">
                                <span>{!! captcha_img() !!}</span>
                                <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="form-group col-md-4">
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-md btn-danger sosad-button">一键注册</button>
                        <h6>本页面含有IP访问频率限制，友情提醒，为了你的正常注册，<code>请不要刷新或倒退</code>页面。</h6>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
