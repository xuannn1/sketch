@extends('layouts.default')
@section('title', '赞助我们')
@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-2 col-sm-8">
        <h1>赞助信息</h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>你好，我们是废文网的运营团队。</h2>
            </div>
            <div class="panel-body">
                <div class="main-text">
                    <p>废文网自2017年成立至今，一直秉持着“创作自由”的观念，为众多文学爱好者提供了一方天地。我们鼓励优质创作，鼓励友好交流，鼓励题材百花齐放。生活中的种种束缚已经太多，我们真诚的希望，在这里，作者能享受自由创作的快乐、读者能享受自由阅读的快乐、不同身份的人都能找到属于自己的怡然自得。目前我们正不断增加网站功能，APP也在研发制作之中。</p>
                    <p>如果你愿意帮助我们实现这个目标，可以前往<em><a href="https://www.patreon.com/sosadfun">Patreon赞助页面</a></em>对我们进行赞助，废文网感谢你的支持！</p>
                    <h6 class="grayout">（暂时只支持海外支付途径）</h6>
                </div>
            </div>
        </div>
        <div class="">
            <a type="button" data-toggle="collapse" data-target="#donation-rewards" style="cursor: pointer;" class="font-3"><h3>赞助者具体福利</h3></a>
        </div>
        <div class="container-fluid collapse" id="donation-rewards">
            @foreach(config('donation') as $key => $donation)
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="font-4">
                            <span class="maintitle">
                                {{ $donation['title'] }}
                            </span>
                        </div>
                        <div class="font-6">
                            <ul>
                                @foreach($donation['rewards'] as $key2 => $reward)
                                <li>{{$reward}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="">
            <a type="button" data-toggle="collapse" data-target="#donation-rewards-redemption" style="cursor: pointer;" class="font-3"><h3>赞助者福利兑换方法</h3></a>
        </div>
        <div class="container-fluid collapse" id="donation-rewards-redemption">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="font-4">
                            <span class="maintitle">
                                如果你是赞助者
                            </span>
                        </div>
                        <div class="font-6">
                            <ul>
                                <li>1.打开“个人中心-赞助者中心”。</li>
                                <li>2.提交“Patreon邮箱”。</li>
                                <li>3.等待工作人员关联数据，一般需要1-3天。</li>
                                <li>4.关联后即自动获得头衔、去广告等福利，也可以在“赞助者中心”生成福利码分发好友。</li>
                                <li>5.粉丝群及作者问答提名等特殊福利，会有专人私信联系。</li>
                                <li>6.更多帮助请搜索关键词“赞助”。</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="font-4">
                            <span class="maintitle">
                                如果你获得了好友的福利码
                            </span>
                        </div>
                        <div class="font-6">
                            <ul>
                                <li>1.打开“个人中心-赞助者中心”。</li>
                                <li>2.点击“兑换福利码”，输入福利码完成兑换。</li>
                                <li>3.更多帮助请搜索关键词“赞助”。</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="font-2">当前赞助名单</span>
                <span class="pull-right">>><a href="#">往期名单</a></span>
            </div>
            <div class="panel-body">
                <div>
                    @include('donations._public_donation_records')
                </div>
            </div>
        </div>
    </div>
</div>
@stop
