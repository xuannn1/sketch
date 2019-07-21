<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;
use DB;
use Cache;
use Carbon\Carbon;
use App\Models\Linkaccount;
use CacheUser;

class LinkedAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = CacheUser::Auser();
        $masteraccounts = $user->masteraccounts;
        $branchaccounts = $user->branchaccounts;
        return view('users.linkaccounts', compact('user', 'masteraccounts', 'branchaccounts'));
    }

    public function index()
    {
        $user = CacheUser::Auser();
        if(!$user){abort(404);}
        $masteraccounts = $user->masteraccounts;
        $branchaccounts = $user->branchaccounts;
        return view('users.linkedaccounts', compact('user', 'masteraccounts', 'branchaccounts'));
    }

    public function store(Request $request)
    {
        $user = CacheUser::Auser();
        $this->validate($request, [
            'email' => 'required',
            'password'  => 'required'
        ]);
        if( $user->branchaccounts()->count() >= ($user->level-4) ){
            return redirect()->back()->with("warning","您的等级限制了您能够关联的账户上限，请升级后再关联更多账户。");
        }
        $newaccount = User::where('email',request('email'))->first();
        if(!$newaccount){
            return redirect()->back()->with("danger","您输入的账号不存在。");
        }
        if(!Hash::check(request('password'), $newaccount->password)){
            return redirect()->back()->with("danger","您输入的账号信息不匹配。");
        }
        if($newaccount->id === $user->id){
            return redirect()->back()->with("danger","抱歉，您不能关联自己的账号。");
        }
        if ($user->linked($newaccount->id)){
            return redirect()->back()->with("warning","您已经关联该账号，请勿重复关联。");
        }

        Linkaccount::create(['master_account'=>$user->id, 'branch_account'=>$newaccount->id]);
        return redirect()->back()->with("success","您已成功关联账号。");
    }

    public function switch($id)
    {
        if(Auth::user()->linked($id)){
            Auth::loginUsingId($id);
            $user = User::findOrFail($id);

            $user->save();
            return redirect()->back()->with("success","您已成功切换账号");
        }else{
            return redirect()->back()->with("danger","您并未关联该账号");
        }
    }

    public function destroy(Request $request)
    {
        $user = CacheUser::Auser();
        if(request()->master_account==$user->id||request()->branch_account==$user->id){
            $link = Linkaccount::where('master_account','=',request()->master_account)
            ->where('branch_account','=',request()->branch_account)->first();
            if ($link){
                $link->delete();
                return ['success' => '您已成功取消关联账号!'];
            }
            return ['warning' => '未找到关联记录！'];
        }
        return ['danger' => '身份信息失误'];
    }
}
