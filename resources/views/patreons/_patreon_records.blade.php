@foreach ($patreon_records as $record)
<div class="row h5">
    <div class="">
        <span>
            赞助人：
            @if($record->user_id===0)
            '未知Patreon赞助人'
            @else
            @if($record->is_anonymous)
            {{$record->patreon_majia}}
            @else
            <a href="{{ route('user.show', $record->user_id) }}">{{ $record->user->name }}</a>
            @endif
            @endif
        </span>
        <span>赞助金额：
            {{$record->show_amount? $record->amount:'赞助者不想展示金额信息'}}
        </span>
        <span>赞助时间：
            {{$record->patreon_at? $patreon_at->setTimezone('Asia/Shanghai'):''}}
        </span>
        <span>赞助留言：
            {{$record->patreon_message? $record->patreon_message:''}}
        </span>
    </div>
</div>
<hr>
@endforeach
