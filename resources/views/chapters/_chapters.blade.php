<div class="hidden-sm hidden-md hidden-lg">
    @foreach($book->chapters as $chapter)
    <a href="{{ route('book.showchapter', $chapter->id) }}" type="button" class = "btn btn-info sosad-button btn-sm btn-block">{{ Helper::convert_to_public($chapter->title) }}：{{ $chapter->mainpost->title }}</a>
    @if(Auth::id()==$thread->user_id)
    <a href="{{ route('book.editchapter', $chapter->id) }}" class="text-center btn-block">编辑{{ Helper::convert_to_public($chapter->title) }}</a>
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
                <th><a href="{{ route('book.showchapter', $chapter->id) }}" class = "">{{ Helper::convert_to_public($chapter->title) }}</a></th>
                <th>{{ $chapter->mainpost->title }}</th>
                <th>{{ $chapter->characters }}</th>
                <th>{{ $chapter->viewed }}</th>
                <th>{{ $chapter->responded }}</th>
                <th>{{ $chapter->created_at }}</th>
                <th>{{ $chapter->edited_at }}</th>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
