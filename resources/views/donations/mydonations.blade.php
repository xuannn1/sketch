@extends('layouts.default')
@section('title', '我的赞助中心')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>「{{$user->name}}」的赞助者中心</h1>
                <h5><a href="https://www.patreon.com/sosadfun">前往Patreon赞助页面支持</a></h5>
                <em><h6 style="color:#d66666">（友情提醒，Patreon默认按月付费，如果你计划一次性付费，请记得及时取消订阅哦～）</h6></em>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>我的赞助记录</h4>
                @include('donations._donation_records')
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body font-5">
                <h4>我的福利现状</h4>
                <div>
                    我的赞助等级：{{ array_key_exists($info->donation_level, config('donation'))? config('donation')[$info->donation_level]['title']:'暂无'}}
                </div>
                <div class="">
                    免广告：{{$user->no_ads?'是':'否'}}
                </div>
                <div class="">
                    免广告福利码余额：{{$info->no_ads_reward_limit}}&nbsp;&nbsp;
                    @if($info->no_ads_reward_limit>0)
                    <a href="{{route('donation.reward_token_create',['type'=>'no_ads'])}}" class="btn btn-xs btn-primary sosad-button-control">消耗额度创建免广告福利码</a>
                    @endif
                </div>
                <div class="">
                    补签福利码余额：{{$info->qiandao_reward_limit}}&nbsp;&nbsp;
                    @if($info->qiandao_reward_limit>0)
                    <a href="{{route('donation.reward_token_create',['type'=>'qiandao+'])}}" class="btn btn-xs btn-primary sosad-button-control">消耗额度创建补签福利码</a>
                    @endif
                    @if($info->qiandao_reward_limit>0&&$info->qiandao_continued<$info->qiandao_last)
                    <span>发现你已断签，当前连续签到：{{ $info->qiandao_continued }}天</span>&nbsp;&nbsp;&nbsp;
                    <span>断签前签到：{{ $info->qiandao_last }}天</span>&nbsp;&nbsp;&nbsp;
                    <a href="{{route('complement_qiandao')}}" class="btn btn-sm btn-primary sosad-button-control">补签至{{$info->qiandao_last+1}}天？</a>
                    @endif
                </div>
                <hr>
                <div class="">
                    <a href="{{route('donation.redeem_token')}}" class="btn btn-md btn-primary sosad-button">兑换他人赠与的福利码</a>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <span class="font-4">我创建的有效福利码</span>
                    <span class="pull-right"><a href="{{route('donation.my_reward_tokens')}}">>>全部福利码</a></span>
                </div>
                <?php $tokens = $reward_tokens ?>
                @include('donations._reward_tokens')
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <h4>「{{$user->name}}」的赞助者身份</h4>
                @if($patreon)
                <div class="form-group">
                    <label for="email">Patreon账户邮箱：</label>
                    <input type="text" name="email" class="form-control" value="{{ $patreon->patreon_email }}" disabled>
                </div>
                @if($patreon->is_approved)
                <p><span class="glyphicon glyphicon-ok">Patreon赞助者信息已验证</span></p>
                @else
                <p><span><i class="fa fa-question-circle-o" aria-hidden="true"></i>&nbsp;Patreon赞助者信息验证中（一般1-3天，感谢你的耐心等待）。</span></p>
                <h6 class="grayout">(如果长时间无法关联，请检查邮箱是否一致。友情提醒，请在此提交在【patreon】网站注册的账户的邮箱，这和废文账户的邮箱不一定一样。邮箱提交错误将无法关联账户信息。其他赞助问题，可直接搜索关键词“赞助”获取:  <a href="https://sosad.fun/search?search=赞助">https://sosad.fun/search?search=赞助</a>)</h6>
                @endif
                <a href="{{route('donation.patreon_destroy_form', $patreon->id)}}" class="btn btn-md btn-primary sosad-button-control">删除我的patreon身份信息</a>
                @else
                <a href="{{route('donation.patreon_create')}}" class="btn btn-md btn-primary sosad-button">提交我的patreon身份信息</a>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
