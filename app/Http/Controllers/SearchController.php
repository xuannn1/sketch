<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CacheUser;
use Cache;
use DB;
use App\Sosadfun\Traits\PageObjectTraits;
use App\Sosadfun\Traits\FAQObjectTraits;

class SearchController extends Controller
{

    use PageObjectTraits;
    use FAQObjectTraits;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1',
        ]);

        $search_result = Cache::remember('search-index-with-pattern-.'.url('/').$request->search,60,function() use($request){
            $threads = $this->find_threads_with_pattern($request);
            $users = $this->find_users_with_pattern($request);
            $tags = $this->find_tags_with_pattern($request);
            $faqs = $this->find_faqs_with_pattern($request);
            return [
                'threads' => $threads,
                'users' => $users,
                'tags' => $tags,
                'faqs' => $faqs,
            ];
        });
        $simplethreads = $search_result['threads'];
        $users = $search_result['users'];
        $tags = $search_result['tags'];
        $faqs = $search_result['faqs'];

        return view('search.search_index', compact('tags','users','simplethreads','faqs'))->with('pattern', $request->search);
    }

    public function search_user(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1',
        ]);
        $pattern = $request->search;
        $page = $request->page;
        $users = $this->find_users_with_pattern($request, 200);

        return view('search.search_users', compact('users'))->with('pattern', $request->search);
    }

    public function search_tag(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1',
        ]);
        $pattern = $request->search;
        $page = $request->page;
        $tags = $this->find_tags_with_pattern($request, 40);

        return view('search.search_tags', compact('tags'))->with('pattern', $request->search);
    }

    public function search_thread(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1',
        ]);
        $simplethreads = $this->find_threads_with_pattern($request, 60);
        return view('search.search_threads', compact('simplethreads'))->with('pattern', $request->search);
    }

    public function find_threads_with_pattern($request)
    {
        $page = is_numeric($request->page)? $request->page:'1';
        $query_id = 'find-threads-with-pattern-'.url('/').$request->search.'-p'.$page;
        return Cache::remember($query_id, 2880, function() use($request) {
            return \App\Models\Thread::with('tags','author')
            ->where('title','like','%'.$request->search.'%')
            ->orderby('responded_at','desc')
            ->paginate(config('preference.threads_per_serach'))
            ->appends($request->only('page','search'));
        });
    }

    public function find_users_with_pattern($request)
    {
        $page = is_numeric($request->page)? $request->page:'1';
        $query_id = 'find-users-with-pattern-'.url('/').$request->search.'-p'.$page;
        return Cache::remember($query_id, 2880, function() use($request) {
            return \App\Models\User::where('name','like','%'.$request->search.'%')
            ->paginate(config('preference.users_per_serach'))
            ->appends($request->only('page','search'));
        });
    }

    public function find_tags_with_pattern($request)
    {
        $page = is_numeric($request->page)? $request->page:'1';
        $query_id = 'find-tags-with-pattern-'.url('/').$request->search.'-p'.$page;
        return Cache::remember($query_id, 2880, function() use($request) {
            return \App\Models\Tag::where('tag_name','like','%'.$request->search.'%')
            ->orWhere('tag_explanation', 'like', '%'.$request->search.'%')
            ->paginate(config('preference.tags_per_serach'))
            ->appends($request->only('page','search'));
        });
    }

    public function find_faqs_with_pattern($request)
    {
        return $this->all_faqs()->filter(function ($value, $key) use($request){
            return preg_match('/'.$request->search.'/', $value->question);
        });
    }
}
