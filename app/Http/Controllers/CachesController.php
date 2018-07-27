<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\TemporaryDataSave;
use Auth;
class CachesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function save(Request $request)
    {
        $item_value = request('item_value');
        if (iconv_strlen($item_value)>10){//大于十个字符，进行自动保存
            $record = TemporaryDataSave::updateOrCreate([
                'user_id' => Auth::id(),
            ],[
                'new' => $item_value,
            ]);
            return "savedcache";
        }else{
            return "notwork";
        }
    }
    public function retrieve()
    {
        $record = TemporaryDataSave::where('user_id',Auth::id())->first();
        if ($record){
            $value = $record->old;
            $record->old = $record->new;
            $record->new = $value;
            $record->save();
            return $value;
        }else{
            return "notwork";
        }
        // $oldkey = Auth::id().'old';
        // $newkey = Auth::id().'new';
        // if(Cache::has($oldkey)){
        //     $value = Cache::get($oldkey);
        //     if (Cache::has($newkey)){
        //         Cache::put($oldkey, Cache::get($newkey),1440);
        //         Cache::put($newkey, $value, 1440);
        //     }
        //     return $value;
        // }else{
        //     return "notwork";
        // }
    }
    public static function initcache()
    {
        $record = TemporaryDataSave::where('user_id',Auth::id())->first();
        if ($record){
            $record->old = $record->new;
            $record->new = null;
            $record->save();
        }
        // if(Cache::has(Auth::id() . 'new')){
        //     Cache::put(Auth::id() . 'old', Cache::get(Auth::id() . 'new'),1440);
        //     // Cache::forget(Auth::id() . 'new');
        // }
        return "initiated";
    }
}
