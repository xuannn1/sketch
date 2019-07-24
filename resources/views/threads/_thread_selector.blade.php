<!-- 进入筛选模式 -->
<div class="selector">
    <div class="dropdown">

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">排序<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('selectors.thread_index_filter.ordered') as $ordered => $explanation)
                    <li><a class="" href="{{ route('threads.index', StringProcess::add_to_thread_filter(['ordered'=>$ordered], request()->all())) }}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>
        @if(Auth::check()&&Auth::user()->isAdmin())
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">私密性<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('selectors.thread_index_filter.isPublic') as $isPublic => $explanation)
                    <li><a class="" href="{{ route('threads.index', StringProcess::add_to_thread_filter(['isPublic'=>$isPublic], request()->all())) }}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">边限<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('selectors.thread_index_filter.withBianyuan') as $withBianyuan => $explanation)
                    <li><a class="" href="{{ route('threads.index', StringProcess::add_to_thread_filter(['withBianyuan'=>$withBianyuan], request()->all())) }}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>
        @endif

    </div>
</div>
