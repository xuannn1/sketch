<div class="hidden-sm hidden-md hidden-lg overflow-hidden">
    @foreach($book->chapters as $chapter)
    <a href="{{ route('book.showchapter', $chapter->id) }}" type="button" class = "sosad-button-chapter text-center">
        @if($chapter->mainpost->bianyuan)
            <span class="glyphicon glyphicon-info-sign"></span>
        @endif
        {{ $chapter->title }}：{{ $chapter->mainpost_info->title }}</a>
    @if(Auth::id()==$thread->user_id)
    <a href="{{ route('book.editchapter', $chapter->id) }}" class="text-center btn-block sosad-button-more grayout smaller-10">编辑{{ $chapter->title }}</a>
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
                <td><a href="{{ route('book.showchapter', $chapter->id) }}" class = "">
                    @if($chapter->mainpost->bianyuan)
                        <span class="glyphicon glyphicon-info-sign"></span>
                    @endif
                    {{ $chapter->title }}</a></td>
                <td class="grayout">{{ $chapter->mainpost_info->title }}</td>
                <td>{{ $chapter->characters }}</td>
                <td>{{ $chapter->viewed }}</td>
                <td>{{ $chapter->responded }}</td>
                <td>{{ Carbon\Carbon::parse($chapter->created_at)->setTimezone('Asia/Shanghai') }}</td>
                <td>{{ Carbon\Carbon::parse($chapter->edited_at)->setTimezone('Asia/Shanghai')
                }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
