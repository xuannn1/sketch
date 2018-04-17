@extends('layouts.default')
@section('title', Helper::convert_to_title($thread->title).'-添加投票')
@section('content')
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>/<a href="{{ route('channel.show', $thread->channel_id) }}">{{ $thread->channel->channelname }}</a>/<a href="{{ route('channel.show', ['channel'=>$thread->channel_id,'label'=>$thread->label_id]) }}">{{ $thread->label->labelname }}</a>/<a href="{{ route('thread.show',$thread->id) }}">{{ Helper::convert_to_title($thread->title) }}</a>/添加投票
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('polls.store', $thread->id) }}" name="create_poll_for_thread">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title"><h4>投票名称：</h4></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="投票名称">
                    </div>

                    <div class="form-group">
                        <label class="radio-inline"><input type="radio" name="option_status" value="1" {{ old('option_status')=='1'?'checked':''}}>单选</label>
                        <label class="radio-inline"><input type="radio" name="option_status" value="2" {{ old('option_status')=='2'?'checked':''}}>多选(请填写选择几项)</label>
                        <input type="number" name="option_status_number" min="1" max="5">
                    </div>
                    <div class="form-group" id="thread-{{$thread->id}}-poll-options">
                        <label for="options"><h4>投票选择项：</h4></label>
                        <input type="text" name="option-1" class="form-control" id="thread-{{$thread->id}}-poll-option-1" value="{{ old('option1') }}" placeholder="请填写选项1">
                        <input type="text" name="option-2" class="form-control" id="thread-{{$thread->id}}-poll-option-2" value="{{ old('option2') }}" placeholder="请填写选项2">
                    </div>
                    <div class="text-center">
                        <button type="button" onclick="addoption({{$thread->id}})" class="addon-button">添加更多选项</button>
                    </div>
                    <div class="form-group">
                        <label for="deadline"><h4>截止日期：</h4></label>
                        <label for="days"><input type="number" name="deadline_days" min="0" max="60">天</label>
                        <label for="hours"><input type="number" name="deadline_hours" min="0" max="60">小时后</label>
                    </div>
                    <div class="text-center">
                        <a data-toggle="collapse" data-target="#more_options" class="h5">（更多设置）</a>
                    </div>

                    <div id="more_options" class="collapse">
                        <div class="form-group">
                            <label for="body"><h4>投票详情(可不填)：</h4></label>
                            <textarea id="mainbody" name="body" rows="4" class="form-control" placeholder="投票的详细信息">{{ old('body') }}</textarea>
                        </div>
                        <div class="reward-options">
                            <h4>额外奖励：</h4>
                            <h6>（分发的额外奖励将从您自身资产扣除。您目前剩余剩饭{{Auth::user()->shengfan}}，咸鱼{{Auth::user()->xianyu}}，丧点{{Auth::user()->sangdian}}。）</h6>
                            <div class="">
                                <label for="reward_xianyu">奖励咸鱼<input type="number" name="reward_xianyu" min="5" max="50">，</label>
                                <label for="reward_shengfan">奖励剩饭<input type="number" name="reward_shengfan" min="20" max="50">，</label>
                                <label for="reward_sangdian">奖励丧点<input type="number" name="reward_shengfan" min="1" max="500">。</label>
                            </div>
                            <div class="">
                                <label>奖励分配方式：</label>
                                前<input type="number" name="reward_receivers" min="1" max="100">名参与投票的人，
                                <label class="radio-inline"><input type="radio" name="reward_distribution" value="1" {{ old('reward_distribution')=='1'?'checked':''}}>均分奖励</label>
                                <label class="radio-inline"><input type="radio" name="reward_distribution" value="2" {{ old('reward_distribution')=='2'?'checked':''}}>随机分配奖励</label>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary sosad-button">发布新投票</button>
                </form>
            </div>
        </div>
    </div>
@stop
