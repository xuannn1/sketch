<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;
use App\Linkaccount;

class LinkedAccountsController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function create()
  {
     if(Auth::user()->linkedaccounts()->count() <= Auth::user()->user_level){
        return view('users.linkaccounts');
     }else{
        return redirect()->back()->with("warning","您的等级限制了您能够关联的账户上限，请升级后再关联更多账户。");
     }
  }
  public function store(Request $request)
  {
      $this->validate($request, [
       'email' => 'required',
       'password'  => 'required'
      ]);
      if(Auth::user()->linkedaccounts()->count() <= Auth::user()->user_level){
         $newaccount = User::where('email',request('email'))->first();
         if ($newaccount){
            if(Hash::check(request('password'), $newaccount->password)){
               if($newaccount->id != Auth::id()){
                  if (Auth::user()->linked($newaccount->id)){
                     return redirect()->back()->with("warning","您已经关联该账号，请勿重复关联。");
                  }else{
                     Linkaccount::create(['account1'=>Auth::id(), 'account2'=>$newaccount->id]);
                  return redirect()->back()->with("success","您已成功关联账号。");
                  }
               }else{
                  return redirect()->back()->with("danger","抱歉，您不能关联自己的账号。");
               }
            }else{
               return redirect()->back()->with("danger","您输入的账号信息不匹配。");
            }
         }else{
            return redirect()->back()->with("danger","您输入的账号不存在。");
         }
      }else{
         return redirect()->back()->with("warning","您的等级限制了您能够关联的账户上限，请升级后再关联更多账户。");
      }
   }
   public function switch($id)
   {
      if(Auth::user()->linked($id)){
         Auth::loginUsingId($id);
         return redirect()->back()->with("success","您已成功切换账号");
      }else{
         return redirect()->back()->with("danger","您并未关联该账号");
      }
   }
   public function destroy($id)
   {
      if(Auth::user()->linked($id)){
         $link = Linkaccount::where('account1','=',$id)->first();
         if ($link){
            $link->delete();
         }
         $link = Linkaccount::where('account2','=',$id)->first();
         if ($link){
            $link->delete();
         }
         return redirect()->back()->with("success","您已成功取消关联账号");
      }else{
         return redirect()->back()->with("danger","您并未关联该账号");
      }
   }
}
