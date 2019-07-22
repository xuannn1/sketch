<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CacheUser;
use Cache;
use DB;
use App\Sosadfun\Traits\PageObjectTraits;

class SearchController extends Controller
{

    use PageObjectTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1|max:6',
        ]);
        $pattern = $request->search;

        $search_result = Cache::remember('search_index.'.$pattern,30,function() use($pattern){
            $threads = $this->find_threads_with_pattern($pattern,20);
            $users = $this->find_users_with_pattern($pattern,40);
            $tags = $this->find_tags_with_pattern($pattern,40);
            $faqs = $this->find_faqs_with_pattern($pattern);
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

        return view('search.search_index', compact('tags','users','simplethreads','pattern','faqs'));
    }

    public function search_user(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1|max:6',
        ]);
        $pattern = $request->search;
        $users = $this->find_users_with_pattern($pattern, 40);

        return view('search.search_users', compact('users','pattern'));
    }

    public function search_tag(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1|max:6',
        ]);
        $pattern = $request->search;
        $tags = $this->find_tags_with_pattern($pattern, 40);

        return view('search.search_tags', compact('tags','pattern'));
    }

    public function search_thread(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1|max:6',
        ]);
        $pattern = $request->search;
        $simplethreads = $this->find_threads_with_pattern($pattern, 40);

        return view('search.search_threads', compact('simplethreads','pattern'));
    }

    public function find_threads_with_pattern($pattern = '', $paginate = 5)
    {
        return \App\Models\Thread::with('tags','author')->where('title','like','%'.$pattern.'%')
        ->paginate($paginate);
    }

    public function find_users_with_pattern($pattern = '', $paginate = 5)
    {
        return \App\Models\User::where('name','like','%'.$pattern.'%')
        ->paginate($paginate);
    }

    public function find_tags_with_pattern($pattern = '', $paginate = 5)
    {
        return \App\Models\Tag::where('tag_name','like','%'.$pattern.'%')
        ->orWhere('tag_explanation', 'like', '%'.$pattern.'%')
        ->paginate($paginate);
    }

    public function find_faqs_with_pattern($pattern = '')
    {
        return $this->all_helpfaqs()->filter(function ($value, $key) use($pattern){
            return preg_match('/'.$pattern.'/', $value->question);
        });
    }
}
