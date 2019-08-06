<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon;
use Cache;
use DB;
use App\Models\Helpfaq;

use App\Sosadfun\Traits\FAQObjectTraits;


class FAQController extends Controller
{
    use FAQObjectTraits;

    public function __construct()
    {
        $this->middleware('admin')->except('index');
    }

    public function index()
    {
        $faqs = $this->find_faqs();
        $webstat = $this->find_web_stats();
        $online_users = $this->find_online_users();
        return view('FAQs.index', compact('faqs','webstat','online_users'));
    }

    private function find_web_stats()
    {
        return Cache::remember('webstat-yesterday', 30, function() {
            return \App\Models\WebStat::latest()->first();
        });
    }

    private function find_online_users()
    {
        return Cache::remember('online_users', 5, function() {
            return DB::table('online_statuses')
            ->where('online_at', '>', Carbon::now()->subMinutes(config('constants.online_count_interval'))->toDateTimeString())
            ->count();
        });
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|min:1|max:6',
        ]);
        $keys = explode('-',$request->key);

        return view('FAQs.create', compact('keys'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|min:1|max:6',
            'question' => 'required|string|min:1|max:180',
            'answer'=>'required|string|min:1|max:2000',
        ]);
        Helpfaq::create($request->only('key','question','answer'));
        $this->clear_all_faqs();
        return redirect()->route('help')->with('success','成功添加FAQ条目');
    }

    public function edit(Helpfaq $faq)
    {
        $keys = explode('-',$faq->key);
        return view('FAQs.edit', compact('faq','keys'));
    }

    public function update(Helpfaq $faq, Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required|string|min:1|max:180',
            'answer'=>'required|string|min:1|max:2000',
        ]);
        $faq->update($request->only('question','answer'));
        $this->clear_all_faqs();
        return redirect()->route('help')->with('success','成功修改FAQ条目');
    }

}
