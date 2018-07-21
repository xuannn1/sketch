<div class="hidden-sm hidden-md hidden-lg overflow-hidden">
    @foreach($book->chapters as $chapter)
    <a href="{{ route('book.showchapter', $chapter->id) }}" type="button" class = "btn btn-info sosad-button btn-sm btn-block">
        @if($chapter->mainpost->bianyuan)
            <span class="glyphicon glyphicon-info-sign"></span>
        @endif
        {{ $chapter->title }}：{{ $chapter->mainpost_info->title }}</a>
    @if(Auth::id()==$thread->user_id)
    <a href="{{ route('book.editchapter', $chapter->id) }}" class="text-center btn-block">编辑{{ $chapter->title }}</a>
    @endif
    @endforeach
</div>
<div class="hidden-xs table-hover">
    <table class="table">
        <thead>
            <tr>
                <th>章节名</th>
                <th>概要</th>
                <th><span class="glyphicon glyphicon-pencil"></span></th>
                <th><span class="glyphicon glyphicon-eye-open"></span></th>
                <th><span class="glyphicon glyphicon-comment"></span></th>
                <th>发布时间</th>
                <th>最后修改</th>
            </tr>
        </thead>
        <tbody>
            @foreach($book->chapters as $chapter)
            <tr>
                <th><a href="{{ route('book.showchapter', $chapter->id) }}" class = "">
                    @if($chapter->mainpost->bianyuan)
                        <span class="glyphicon glyphicon-info-sign"></span>
                    @endif
                    {{ $chapter->title }}</a></th>
                <th>{{ $chapter->mainpost_info->title }}</th>
                <th>{{ $chapter->characters }}</th>
                <th>{{ $chapter->viewed }}</th>
                <th>{{ $chapter->responded }}</th>
                <th>{{ Carbon\Carbon::parse($chapter->created_at)->setTimezone('Asia/Shanghai') }}</th>
                <th>{{ Carbon\Carbon::parse($chapter->edited_at)->setTimezone('Asia/Shanghai')
                     }}</th>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
