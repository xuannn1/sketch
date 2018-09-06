@extends('layouts.default')
@section('title', '联系我们')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>联系我们</h2>
                </div>
                <div class="panel-heading">
                    <h3>废文编辑组</h3>
                </div>
                <div class="panel-body">
                    <h4>废文编辑组于2018年7月成立。编辑组成员简介及联系方式如下。欢迎各位作者自荐！</h4>
                    <p class="grayout">友情提示：请勿同时批量自荐哦～</p>
                </div>
                <div class="panel-body">
                    <h4>北北</h4>
                    <ul>
                        <li>无任何雷点，各种类型都看，欢迎自荐！</li>
                        <li>推荐书目：《以身相许》《赤酒情歌》《死亡无法阻止我爱你》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58366) }}">@北北北北</a> </li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4>豆奶</h4>
                    <ul>
                        <li>荤素不忌，偏好日常向现实文。</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58233) }}">@无机豆奶</a> </li>
                        <li>欢迎来嘬~</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>豆芽</h4>
                    <ul>
                        <li>爱好发呆，喜食蜜三刀，糖里藏刀，刀上黏糖。同人文倾向纸片人，狂追文笔架构，逻辑一直是硬伤。</li>
                        <li>推荐书目：《救赎》《下一把我拉你》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58238) }}">@豆芽</a> </li>
                        <li>欢迎向我推荐或自荐作品~</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>风球</h4>
                    <ul>
                        <li>爱好脆皮鸭，尤其喜欢美人受。古风现代都吃，偏好现代爱情故事。不吃年下和互攻！打死我也不吃！文笔特别好的情况下，可能会突发真香。</li>
                        <li>推荐书目：《夜莺》《道貌岸然》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58228) }}">@风球</a> </li>
                        <li>欢迎向我卖安利呀，自荐或者推荐都可以～</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>二筒</h4>
                    <ul>
                        <li>爱好摸鱼打磕，害羞社恐党，BGBL都看不挑食，文笔好啥都吃</li>
                        <li>推荐书目：《第十二封情书》《多维过客》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 56346) }}">@一只二筒筒</a> </li>
                        <li>欢迎问题箱自荐推荐；暂无微博</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>光生</h4>
                    <ul>
                        <li>一个拥有大众口味的白日梦选手，特长是做白日梦。</li>
                        <li>不挑食，任何作品都能看进去，偏爱风格独特或角色魅力十足的作品。</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 48493) }}">@光生光</a> </li>
                        <li>欢迎向我推荐你喜欢的作品或自荐！</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>南风</h4>
                    <ul>
                        <li>爱好摸鱼，最喜欢在ddl前打游戏，偏好BL，BG也吃，喜欢小甜饼也喜欢悬疑恐怖类</li>
                        <li>推荐书目：《第十二封情书》《良师》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58229) }}">@南风风</a> </li>
                        <li>欢迎向我推荐或自荐作品~</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>闲令</h4>
                    <ul>
                        <li>爱好划水躺尸，深海电波杂食系。非常规小甜饼控，无雷型读者。</li>
                        <li>推荐书目：《荆棘之窗》《浓雾与海盐之礁》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58230) }}">@闲令</a> </li>
                        <li>欢迎推文与自荐♡</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>1551</h4>
                    <ul>
                        <li>喜欢画画，特技是用同人作品来催更，是个喜欢正剧，对小甜饼过敏的文笔控。</li>
                        <li>推荐书目：《暗涌》《防不胜防楚大侠》</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58227) }}">@1551君</a> </li>
                        <li>欢迎向我推荐或自荐，刀子玻璃渣什么的大大欢迎！</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>一盏废灯</h4>
                    <ul>
                        <li>杂食系生物，略执着于人情味，偏爱任何题材故事里的烟火气，不忌悲喜。</li>
                        <li>联系方式：废文<a href="{{ route('user.show', 58272) }}">@一盏废灯</a> </li>
                        <li>走过路过，希望各位大佬多多投喂！</li>
                    </ul>
                </div>

                <div class="panel-body">
                    <h4>Y酱</h4>
                    <ul>
                        <li>推荐书目：《冰原》《浪潮》</li>
                        <li>重度社恐，保持神秘……</li>
                    </ul>
                </div>
                <h6>（编辑组信息按姓名拼音排序，推荐书目为编辑在网站物色并写作长短评推荐过的别的作者的文章）</h6>
            </div>
        </div>
    </div>
@stop
