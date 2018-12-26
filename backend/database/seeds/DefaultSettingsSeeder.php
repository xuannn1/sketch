<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DefaultSettingsSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        {
            DB::table('channels')->insert([
                'channel_name' => '原创小说',//Channel No.1
                'channel_explanation' => '原创小说板块从此进入',
                'order_by' => '1',
                'channel_rule' => '原创区版规是……',
                'channel_state' => '1',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '同人小说',//Channel No.2
                'channel_explanation' => '同人衍生小说板块从此进入',
                'order_by' => '2',
                'channel_rule' => '同人区版规是……',
                'channel_state' => '1',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '作业专区',//Channel No.2
                'channel_explanation' => '站内作业板块从此进入',
                'order_by' => '3',
                'channel_rule' => '作业区的版规是……',
                'channel_state' => '10',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '读写交流',//Channel No.2
                'channel_explanation' => '读写交流板块从此进入',
                'order_by' => '4',
                'channel_rule' => '读写交流区的版规是……',
                'channel_state' => '0',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '日常闲聊',//Channel No.2
                'channel_explanation' => '闲谈、吐槽、求助、八卦、安利',
                'order_by' => '5',
                'channel_rule' => '日常闲聊区的版规是……',
                'channel_state' => '0',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '随笔',//Channel No.2
                'channel_explanation' => '随笔板块从此进入',
                'order_by' => '6',
                'channel_rule' => '随笔区的版规是……',
                'channel_state' => '0',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '站务管理',//Channel No.2
                'channel_explanation' => '站务管理板块从此进入',
                'order_by' => '7',
                'channel_rule' => '站务管理区的版规是……',
                'channel_state' => '0',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '违规举报',//Channel No.2
                'channel_explanation' => '违规举报板块从此进入',
                'order_by' => '8',
                'channel_rule' => '违规举报区的版规是……',
                'channel_state' => '2',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '投诉仲裁',//Channel No.2
                'channel_explanation' => '投诉仲裁板块从此进入',
                'order_by' => '9',
                'channel_rule' => '投诉仲裁区的版规是……',
                'channel_state' => '3',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '后台管理',//Channel No.2
                'channel_explanation' => '后台管理板块从此进入',
                'order_by' => '10',
                'channel_rule' => '后台管理区的版规是……',
                'channel_state' => '25',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '档案',//Channel No.2
                'channel_explanation' => '档案板块从此进入',
                'order_by' => '11',
                'channel_rule' => '档案区的版规是……',
                'channel_state' => '25',
            ]);

            DB::table('channels')->insert([
                'channel_name' => '后花园',//Channel No.2
                'channel_explanation' => '后花园从此进入',
                'order_by' => '12',
                'channel_rule' => '后花园的版规是……',
                'channel_state' => '20',
            ]);
        }
        {//预设大类标签//labels
            {//（板块：日常闲聊 Channel No.5） 闲谈、吐槽、求助、八卦、安利
                DB::table('labels')->insert([
                    'label_name' => '闲谈',
                    'channel_id' => '5',//日常闲聊
                    'label_explanation' => '闲谈',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '吐槽',
                    'channel_id' => '5',//日常闲聊
                    'label_explanation' => '吐吐更健康',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '求助',
                    'channel_id' => '5',//日常闲聊
                    'label_explanation' => '来人啊，大事不好了',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '汇总',
                    'channel_id' => '5',//日常闲聊
                    'label_explanation' => '做了一点微小的工作',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '安利分享',
                    'channel_id' => '5',//日常闲聊
                    'label_explanation' => '断头、吐血，吃我一口毒奶',
                ]);
            }
            {//11（板块：读写交流 Channel No.5） 分享、探讨、评文、自荐、推文
                DB::table('labels')->insert([
                    'label_name' => '写作探讨',
                    'channel_id' => '4',
                    'label_explanation' => '除了卡文，也许还可以做点别的',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '评文推文',
                    'channel_id' => '4',
                    'label_explanation' => '我有一句…不吐不快',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '自荐求评',
                    'channel_id' => '4',
                    'label_explanation' => '鼓足勇气的第一步',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '读写活动',
                    'channel_id' => '4',
                    'label_explanation' => '来自作业区的问候',
                ]);
            }

            {//21（板块：站务管理 Channel No.7）账号、建议意见、bug汇报、站务公告
                DB::table('labels')->insert([
                    'label_name' => '账号',
                    'channel_id' => '7',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '建议意见',
                    'channel_id' => '7',
                ]);
                DB::table('labels')->insert([
                    'label_name' => 'bug汇报',
                    'channel_id' => '7',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '站务公告',
                    'channel_id' => '7',
                ]);
            }
            {//22（板块：违规举报 Channel No.8）人身攻击、信息泄露、待处理、处理中、历史记录
                DB::table('labels')->insert([
                    'label_name' => '人身攻击',
                    'channel_id' => '8',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '信息泄露',
                    'channel_id' => '8',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '待处理',
                    'channel_id' => '8',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '处理中',
                    'channel_id' => '8',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '历史记录',
                    'channel_id' => '8',
                ]);
            }
            {//23（板块：投诉仲裁 Channel No.9）管理投诉、权益仲裁、待处理、处理中、历史记录
                DB::table('labels')->insert([
                    'label_name' => '管理投诉',
                    'channel_id' => '9',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '权益仲裁',
                    'channel_id' => '9',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '待处理',
                    'channel_id' => '9',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '处理中',
                    'channel_id' => '9',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '历史记录',
                    'channel_id' => '9',
                ]);
            }
            {//31 （板块：管理组 Channel No.10) 处理投诉、日常管理、仲裁相关
                DB::table('labels')->insert([
                    'label_name' => '处理投诉',
                    'channel_id' => '10',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '日常管理',
                    'channel_id' => '10',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '仲裁相关',
                    'channel_id' => '10',
                ]);
            }
            {//32 （板块：管理组 Channel No.10) 处理投诉、日常管理、仲裁相关
                DB::table('labels')->insert([
                    'label_name' => '建站相关',
                    'channel_id' => '11',
                ]);
                DB::table('labels')->insert([
                    'label_name' => '2017',
                    'channel_id' => '10',
                ]);
            }
            {//文库相关label预设 （原创大类 同人大类）
                {//（原创大类 Channel No.1）古代 现代 民国 西方 奇幻 科幻 灵异 玄幻 网游 其他
                    DB::table('labels')->insert([
                        'label_name' => '古代',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '现代',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '民国',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '西方',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '奇幻',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '科幻',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '灵异',
                        'channel_id' => '1',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '玄幻',
                        'channel_id' => '1',
                    ]);

                    DB::table('labels')->insert([
                        'label_name' => '其他',
                        'channel_id' => '1',
                    ]);

                }
                {//（同人大类 Channel No.2）影视、动漫、游戏、小说、真人、其他
                    DB::table('labels')->insert([
                        'label_name' => '影视',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '动漫',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '游戏',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '小说',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '真人',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '其他',
                        'channel_id' => '2',
                    ]);
                }

                {//（同人大类 Channel No.2）影视、动漫、游戏、小说、真人、其他
                    DB::table('labels')->insert([
                        'label_name' => '本次',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '往期',
                        'channel_id' => '2',
                    ]);
                }
                {
                    DB::table('labels')->insert([
                        'label_name' => '随笔',
                        'channel_id' => '6',
                    ]);
                }
                {
                    DB::table('labels')->insert([
                        'label_name' => '后花园',
                        'channel_id' => '30',
                    ]);
                }

                {
                    DB::table('labels')->insert([
                        'label_name' => '其他',
                        'channel_id' => '3',
                    ]);
                }


                {
                    DB::table('labels')->insert([
                        'label_name' => '历史',
                        'channel_id' => '2',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '戏剧',
                        'channel_id' => '2',
                    ]);
                }

                {
                    DB::table('labels')->insert([
                        'label_name' => '散文',
                        'channel_id' => '6',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '诗歌',
                        'channel_id' => '6',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '日志',
                        'channel_id' => '6',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '其他',
                        'channel_id' => '6',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '脑洞',
                        'channel_id' => '6',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '翻译',
                        'channel_id' => '6',
                    ]);
                }
                {
                    DB::table('labels')->insert([
                        'label_name' => '日常交互',
                        'channel_id' => '7',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '日常违规',
                        'channel_id' => '8',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '文字游戏',
                        'channel_id' => '5',
                    ]);
                    DB::table('labels')->insert([
                        'label_name' => '读书记录',
                        'channel_id' => '4',
                    ]);
                }


                //通用tag-0
                //
                //边缘tag-5
                //同人原著题材-10
                //同人cp-20

                {//（萌梗）强强、破镜重圆、1v1、狗血、哨兵向导、娱乐圈
                    DB::table('tags')->insert([
                        'tag_name' => '强强',
                        'tag_group' => '0',
                        'tag_info' => '5',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '破镜重圆',
                        'tag_group' => '0',
                        'tag_info' => '13',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '1v1',
                        'tag_group' => '0',
                        'tag_info' => '7',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '狗血',
                        'tag_group' => '0',
                        'tag_info' => '4',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '哨兵向导',
                        'tag_group' => '0',
                        'tag_info' => '15',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '娱乐圈',
                        'tag_group' => '0',
                        'tag_info' => '11',
                    ]);
                }
                {//（同人原著）-2
                    DB::table('tags')->insert([
                        'tag_name' => '全职',
                        'tag_info' => '45',
                        'tag_group' => '10',
                    ]);
                }
                {//（同人cp）-3
                    DB::table('tags')->insert([
                        'tag_name' => '周叶',
                        'tag_group' => '20',
                    ]);
                }
                {//（边缘）-5 文章含肉超过20%，或题材包含人兽、触手、父子、乱伦、生子、产乳、abo、军政、黑道、性转
                    DB::table('tags')->insert([
                        'tag_name' => '高H',
                        'tag_group' => '5',
                        'tag_info' => '9',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '人兽',
                        'tag_group' => '5',
                        'tag_info' => '17',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '触手',
                        'tag_group' => '5',
                        'tag_info' => '17',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '父子',
                        'tag_group' => '5',
                        'tag_info' => '6',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '乱伦',
                        'tag_group' => '5',
                        'tag_info' => '6',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '生子',
                        'tag_group' => '5',
                        'tag_info' => '6',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '产乳',
                        'tag_group' => '5',
                        'tag_info' => '17',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => 'abo',
                        'tag_group' => '5',
                        'tag_info' => '15',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '黑道',
                        'tag_group' => '5',
                        'tag_info' => '11',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '性转',
                        'tag_group' => '5',
                        'tag_info' => '12',
                    ]);
                }


            }
        }
        // InvitationToken::create([
        //     'user_id' => 1,
        //     'token' => 'SOSAD_invite',
        //     'invitation_times' => 10,
        //     'invite_until' => Carbon::now()->addYears(2),
        // ]);
    }
}
