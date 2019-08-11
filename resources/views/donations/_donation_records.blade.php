@foreach ($donation_records as $record)
<div class="row h5">
    <div class="">
        <span class="badge bianyuan-tag badge-tag">{{$record->donation_kind}}</span>
        <span class="badge newchapter-badge badge-tag">{{$record->is_claimed? '已兑换':'未兑换'}}</span>
        <span class="">{{$record->donation_email}}</span>
        <span>
            「
            @if($record->user_id===0)
            '未知Patreon赞助人'
            @else
            @if($record->is_anonymous)
            {{$record->donation_majia??'匿名咸鱼'}}
            @else
            <a href="{{ route('user.show', $record->user_id) }}">{{ $record->user->name }}</a>
            @endif
            @endif
            」
        </span>
        <span>
            {{$record->show_amount? '$'.$record->donation_amount:'赞助者不想展示金额信息'}}
        </span>
        <span>
            {{$record->donated_at? $record->donated_at->setTimeZone('Asia/Shanghai'):''}}
        </span>
        @if($record->donation_message)
        <span>留言：
            {{$record->donation_message}}
        </span>&nbsp;
        @endif
        @if($info->donation_level>1)
        <a href="{{route('donation.donation_edit', $record->id)}}" class="btn btn-xs btn-primary sosad-button-control">修改记录描述</a>
        @endif
    </div>
</div>
@endforeach
