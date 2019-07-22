<!-- 进入筛选模式 -->
<div class="selector">
    <div class="dropdown">
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">类型<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['withType'] as $withType => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'withType' => $withType], request()->only('withComponent', 'withFolded', 'userOnly', 'withReplyTo', 'ordered'))) }}">{{$explanation}}</a></li>
                @endforeach

            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">内容<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['withComponent'] as $withComponent => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'withComponent' => $withComponent], request()->only('withType','withFolded', 'userOnly', 'withReplyTo', 'ordered'))) }}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">折叠情况<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['withFolded'] as $withFolded => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'withFolded' => $withFolded], request()->only('withType', 'userOnly', 'withReplyTo', 'ordered'))) }}">{{$explanation}}</a></li>
                @endforeach

            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">排序<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['ordered'] as $ordered => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'ordered' => $ordered], request()->only('withType', 'withComponent','withFolded', 'userOnly', 'withReplyTo'))) }}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>
    </div>
</div>
