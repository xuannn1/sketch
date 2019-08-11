@foreach ($donation_records as $record)
<div class="row h5">
    <div class="">
        <span>
            「
            @if($record->user_id===0)
            '未知Patreon赞助人'
            @else
            @if($record->is_anonymous)
            {{$record->patreon_majia??'匿名咸鱼'}}
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
            {{$record->donated_at? $record->donated_at->diffForHumans():''}}
        </span>
        @if($record->donation_message)
        <span>留言：
            {{$record->donation_message}}
        </span>
        @endif
    </div>
</div>
@endforeach
