<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sosadfun\Traits\DonationObjectTraits;
use CacheUser;
use Carbon;
use Cache;
use DB;
class DonationController extends Controller
{
    use DonationObjectTraits;

    public function __construct()
    {
        $this->middleware('auth')->except('donate');
        // $this->middleware('admin')->only();
    }

    public function donate()
    {
        $donation_records = $this->RecentDonations();
        return view('donations.donate', compact('donation_records'));
    }

    public function mydonations()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $patreon = $user->patreon;
        $donation_records = $user->donation_records;
        $reward_tokens = $user->reward_tokens->where('is_public',0);
        $reward_tokens->sortByDesc('created_at');

        return view('donations.mydonations',compact('user', 'info', 'patreon', 'reward_tokens', 'donation_records'));
    }

    public function patreon_create()
    {
        $user = CacheUser::Auser();
        $patreon = \App\Models\Patreon::on('mysql::write')->where('user_id',$user->id)->first();
        if($patreon){abort(409);}
        return view('donations.patreon_create');
    }

    public function patreon_store(Request $request)
    {
        $user = CacheUser::Auser();
        $patreon = \App\Models\Patreon::on('mysql::write')->where('user_id',$user->id)->first();
        if($patreon){abort(409);}

        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $other_record = \App\Models\Patreon::on('mysql::write')->where('patreon_email', $request->email)->first();
        if($other_record){
            return redirect()->back()->with('danger','这个Patreon账户已经有人申请，如果不是您本人，请联系站务人员申诉。');
        }
        \App\Models\Patreon::create([
            'user_id' => $user->id,
            'patreon_email' => $request->email,
        ]);
        return redirect()->route('donation.mydonations')->with('success','已成功提交patreon信息，请等待工作人员完成数据关联。');
    }

    public function patreon_destroy($id)
    {
        $user = CacheUser::Auser();
        $patreon = \App\Models\Patreon::on('mysql::write')->find($id);
        if(!$patreon){abort(404);}
        if($patreon->user_id!=$user->id){abort(403);}
        $patreon->delete();

        // TODO::取消相关福利
        $user->remove_donation();

        return redirect()->route('donation.mydonations')->with('success','已删除patreon关联信息。');
    }

    public function patreon_destroy_form($id)
    {
        $user = CacheUser::Auser();
        $patreon = \App\Models\Patreon::on('mysql::write')->find($id);
        if(!$patreon){abort(404);}
        if($patreon->user_id!=$user->id){abort(403);}

        return view('donations.patreon_destroy_form', compact('patreon','user'));
    }

    public function approve_patreon($id)
    {
        $user = CacheUser::user();
        $user->reward_donation();
        return redirect()->back()->with('success','已核准兑换');
    }

    public function redeem_token_form()
    {
        return view('donations.redeem_token_form');
    }

    public function redeem_token(Request $request)
    {
        if (Cache::has('redeem-reward-limit-' . request()->ip())){
            return back()->with('danger','本ip('.request()->ip().')已于2分钟内尝试兑换，请等待冷静期经过，请勿重复输入信息或试图暴力破解福利码');
        }
        Cache::put('redeem-reward-limit-' . request()->ip(), true, 2);

        $user = CacheUser::Auser();
        $request->validate([
            'token' => 'required|string|max:30',
        ]);
        $reward_token = \App\Models\RewardToken::where('token',$request->token)->first();

        if(!$reward_token){return back()->with('danger','福利码不存在，请检查拼写正确');}

        if($reward_token->redeem_limit<=0||$reward_token->redeem_until<Carbon::now()){
            return back()->with('danger','福利码已失效');
        }
        if($reward_token->type==='no_ads'&&$user->no_ads){
            return back()->with('info','你已经具有去广告福利，无需浪费这个福利码');
        }

        DB::transaction(function()use($user, $reward_token){
            $reward_token->redeem_count+=1;
            $reward_token->redeem_limit-=1;
            $reward_token->save();
            \App\Models\RewardTokenRedemption::create([
                'user_id' => $user->id,
                'token_creator_id' => $reward_token->user_id,
                'token_id' => $reward_token->id,
            ]);
            if($reward_token->type=='no_ads'){
                $user->no_ads = 1;
                $user->save();
            }
            if($reward_token->type=='qiandao+'){
                $user->info->qiandao_reward_limit+=1;
                $user->info->save();
            }
        });

        return redirect()->route('donation.mydonations')->with('success','已兑换福利码');
    }
}
