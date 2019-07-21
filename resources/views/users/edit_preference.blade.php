@extends('layouts.default')
@section('title', '编辑个人偏好')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>编辑个人偏好</h1>
            </div>
            <br>
            <div class="panel-body">
                <form method="POST" action="{{ route('user.update_preference') }}">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="no_upvote_reminders"{{$info->no_upvote_reminders?'checked':''}}>是否不再接收点赞提醒？</label><br>
                                <label><input type="checkbox" name="no_reward_reminders"{{$info->no_reward_reminders?'checked':''}}>是否不再接收打赏提醒？</label><br>
                                <label><input type="checkbox" name="no_message_reminders"{{$info->no_message_reminders?'checked':''}}>是否不再接收私信提醒？</label><br>
                                <label><input type="checkbox" name="no_reply_reminders"{{$info->no_reply_reminders?'checked':''}}>是否不再接收回复提醒？</label><br>
                                <label><input type="checkbox" name="no_stranger_msg"{{$info->no_stranger_msg?'checked':''}}>是否不再接收陌生人私信？</label><br>
                            </div>
                        </div>
                        @if($groups)
                        <div class="form-group">
                            <label for="default_collection_group_id">新建立的书籍添加到哪个收藏夹？</label><br>
                            @foreach($groups as $group)
                            <label class="radio-inline"><input class="" type="radio" name="default_collection_group_id" value="{{ $group->id }}" {{$info->default_collection_group_id===$group->id ? 'checked':''}}>{{ $group->name }}</label><br>
                            @endforeach
                        </div>
                        @endif
                        @if($lists)
                        <div class="form-group">
                            <label for="default_collection_group_id">新建立的书评添加到哪个清单？</label><br>
                            @foreach($lists as $list)
                            <label class="radio-inline"><input class="" type="radio" name="default_list_id" value="{{ $list->id }}" {{$info->default_list_id===$list->id ? 'checked':''}}>{{ $list->title }}</label><br>
                            @endforeach
                        </div>
                        @endif

                    <button type="submit" class="btn btn-md btn-danger sosad-button">更新个人偏好信息</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
