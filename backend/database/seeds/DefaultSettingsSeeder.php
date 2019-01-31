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
        // {
        //     DB::table('channels')->insert([
        //         'id' => 1,
        //         'channel_name' => '原创小说',//Channel No.1
        //         'channel_explanation' => '原创小说板块从此进入',
        //         'order_by' => '1',
        //         'type' => 'book',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 2,
        //         'channel_name' => '同人衍生',//Channel No.2
        //         'channel_explanation' => '同人衍生板块从此进入',
        //         'order_by' => '2',
        //         'type' => 'book',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 3,
        //         'channel_name' => '作业专区',//Channel No.2
        //         'channel_explanation' => '站内作业板块从此进入',
        //         'order_by' => '3',
        //         'is_public' => false,
        //         'type' => 'homework',
        //         'on_homepage' => false,
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 4,
        //         'channel_name' => '读写交流',//Channel No.2
        //         'channel_explanation' => '读写交流板块从此进入',
        //         'order_by' => '4',
        //         //'channel_rule' => '读写交流区的版规是……',
        //         'type' => 'thread',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 5,
        //         'channel_name' => '日常闲聊',//Channel No.2
        //         'channel_explanation' => '闲谈、吐槽、求助、八卦、安利',
        //         'order_by' => '5',
        //         //'channel_rule' => '日常闲聊区的版规是……',
        //         'type' => 'thread',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 6,
        //         'channel_name' => '随笔',//Channel No.2
        //         'channel_explanation' => '随笔板块从此进入',
        //         'order_by' => '6',
        //         //'channel_rule' => '随笔区的版规是……',
        //         'type' => 'thread',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 7,
        //         'channel_name' => '站务管理',//Channel No.2
        //         'channel_explanation' => '站务管理板块从此进入',
        //         'order_by' => '7',
        //         //'channel_rule' => '站务管理区的版规是……',
        //         'type' => 'thread',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 8,
        //         'channel_name' => '违规举报',//Channel No.2
        //         'channel_explanation' => '违规举报板块从此进入',
        //         'order_by' => '8',
        //         //'channel_rule' => '违规举报区的版规是……',
        //         'allow_edit' => false,
        //         'type' => 'request',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 9,
        //         'channel_name' => '投诉仲裁',//Channel No.2
        //         'channel_explanation' => '投诉仲裁板块从此进入',
        //         'order_by' => '9',
        //         //'channel_rule' => '投诉仲裁区的版规是……',
        //         'allow_edit' => false,
        //         'allow_anonymous' => false,
        //         'type' => 'request',
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 10,
        //         'channel_name' => '后台管理',//Channel No.2
        //         'channel_explanation' => '后台管理板块从此进入',
        //         'order_by' => '10',
        //         //'channel_rule' => '后台管理区的版规是……',
        //         'is_public' => false,
        //         'type' => 'thread',
        //         'on_homepage' => false,
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 11,
        //         'channel_name' => '档案',//Channel No.2
        //         'channel_explanation' => '档案板块从此进入',
        //         'order_by' => '11',
        //         //'channel_rule' => '档案区的版规是……',
        //         'is_public' => false,
        //         'type' => 'thread',
        //         'on_homepage' => false,
        //     ]);
        //
        //     DB::table('channels')->insert([
        //         'id' => 12,
        //         'channel_name' => '后花园',//Channel No.2
        //         'channel_explanation' => '后花园从此进入',
        //         'order_by' => '12',
        //         //'channel_rule' => '后花园的版规是……',
        //         'is_public' => false,
        //         'type' => 'thread',
        //         'on_homepage' => false,
        //     ]);
        // }
        {//大类标签
            {//（同人大类 Channel No.2）影视、动漫、游戏、小说、真人、其他
                DB::table('tags')->insert([
                    'tag_name' => '影视',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '动漫',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '游戏',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '小说',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '真人',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '历史',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '戏剧',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '其他同人',
                    'channel_id' => '2',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//（作业 Channel No.3）作业专区
                DB::table('tags')->insert([
                    'tag_name' => '本次作业',
                    'channel_id' => '3',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '往期作业',
                    'channel_id' => '3',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '其他作业',
                    'channel_id' => '3',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//（板块：读写交流 Channel No.4） 分享、探讨、评文、自荐、推文
                DB::table('tags')->insert([
                    'tag_name' => '写作探讨',
                    'channel_id' => '4',
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '除了卡文，也许还可以做点别的',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '评文推文',
                    'channel_id' => '4',
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '我有一句…不吐不快',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '自荐求评',
                    'channel_id' => '4',
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '鼓足勇气的第一步',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '读书记录',
                    'channel_id' => '4',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '读写活动',
                    'channel_id' => '4',
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '来自作业区的问候',
                ]);
            }
            {//（板块：日常闲聊 Channel No.5） 闲谈、吐槽、求助、八卦、安利
                DB::table('tags')->insert([
                    'tag_name' => '闲谈',
                    'channel_id' => '5',//日常闲聊
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '闲谈',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '吐槽',
                    'channel_id' => '5',//日常闲聊
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '吐吐更健康',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '求助',
                    'channel_id' => '5',//日常闲聊
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '来人啊，大事不好了',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '汇总',
                    'channel_id' => '5',//日常闲聊
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '做了一点微小的工作',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '安利分享',
                    'channel_id' => '5',//日常闲聊
                    'tag_type' => '大类',
                    'is_primary' => true,
                    'tag_explanation' => '断头、吐血，吃我一口毒奶',
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '文字游戏',
                    'channel_id' => '5',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {
                DB::table('tags')->insert([
                    'tag_name' => '随笔',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);

                DB::table('tags')->insert([
                    'tag_name' => '散文',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '诗歌',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '日志',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '戏剧',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '脑洞',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '翻译',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '影评乐评',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '其他随笔',
                    'channel_id' => '6',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }

            {//（板块：站务管理 Channel No.7）账号、建议意见、bug汇报、站务公告
                DB::table('tags')->insert([
                    'tag_name' => '日常交互',
                    'channel_id' => '7',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '账号',
                    'channel_id' => '7',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '建议意见',
                    'channel_id' => '7',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => 'bug汇报',
                    'channel_id' => '7',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '站务公告',
                    'channel_id' => '7',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//22（板块：违规举报 Channel No.8）人身攻击、信息泄露、待处理、处理中、历史记录
                DB::table('tags')->insert([
                    'tag_name' => '日常违规',
                    'channel_id' => '8',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '人身攻击',
                    'channel_id' => '8',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '信息泄露',
                    'channel_id' => '8',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '历史记录',
                    'channel_id' => '8',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//23（板块：投诉仲裁 Channel No.9）管理投诉、权益仲裁、待处理、处理中、历史记录
                DB::table('tags')->insert([
                    'tag_name' => '管理投诉',
                    'channel_id' => '9',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '权益仲裁',
                    'channel_id' => '9',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '历史记录',
                    'channel_id' => '9',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//31 （板块：管理组 Channel No.10) 处理投诉、日常管理、仲裁相关
                DB::table('tags')->insert([
                    'tag_name' => '处理投诉',
                    'channel_id' => '10',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '日常管理',
                    'channel_id' => '10',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                DB::table('tags')->insert([
                    'tag_name' => '仲裁相关',
                    'channel_id' => '10',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//32 （板块：管理组 Channel No.11) 档案
                DB::table('tags')->insert([
                    'tag_name' => '建站相关',
                    'channel_id' => '11',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//32 （板块：管理组 Channel No.11) 后花园
                DB::table('tags')->insert([
                    'tag_name' => '后花园',
                    'channel_id' => '12',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
            {//文库相关tag预设 （原创大类 同人大类）
                {//（原创大类 Channel No.1）古代 现代 民国 西方 奇幻 科幻 灵异 玄幻 网游 其他
                    DB::table('tags')->insert([
                        'tag_name' => '古代',
                        'tag_type' => '整体时代',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '现代',
                        'tag_type' => '整体时代',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '民国',
                        'tag_type' => '整体时代',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '西方',
                        'tag_type' => '风俗环境',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '奇幻',
                        'tag_type' => '世界设定',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '科幻',
                        'tag_type' => '世界设定',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '灵异',
                        'tag_type' => '世界设定',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '玄幻',
                        'tag_type' => '世界设定',
                        'is_primary' => true,
                    ]);

                }
                {//（萌梗）强强、破镜重圆、1v1、狗血、哨兵向导、娱乐圈
                    DB::table('tags')->insert([
                        'tag_name' => '强强',
                        'tag_type' => '强弱关系',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '破镜重圆',
                        'tag_type' => '具体情节',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '1v1',
                        'tag_type' => 'CP关系',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '狗血',
                        'tag_type' => '故事观感',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '哨兵向导',
                        'tag_type' => '生物设定',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '娱乐圈',
                        'tag_type' => '职业范围',
                    ]);
                }
                {//（同人原著）-2
                    $tongren_yuanzhu_id = DB::table('tags')->insertGetId([
                        'tag_name' => '全职',
                        'tag_type' => '同人原著',
                        'channel_id' => 2,
                    ]);
                }
                {//（同人cp）-3
                    DB::table('tags')->insert([
                        'tag_name' => '周叶',
                        'tag_type' => '同人CP',
                        'channel_id' => 2,
                        'parent_id' => $tongren_yuanzhu_id,
                    ]);
                }
                {//（边缘）-5 文章含肉超过20%，或题材包含人兽、触手、父子、乱伦、生子、产乳、abo、军政、黑道、性转
                    DB::table('tags')->insert([
                        'tag_name' => '人兽',
                        'is_bianyuan' => true,
                        'tag_type' => '性癖',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '触手',
                        'is_bianyuan' => true,
                        'tag_type' => '性癖',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '父子',
                        'is_bianyuan' => true,
                        'tag_type' => '伦理关系'
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '乱伦',
                        'is_bianyuan' => true,
                        'tag_type' => '伦理关系'
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '生子',
                        'is_bianyuan' => true,
                        'tag_type' => '伦理关系'
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '产乳',
                        'is_bianyuan' => true,
                        'tag_type' => '性癖',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => 'abo',
                        'is_bianyuan' => true,
                        'tag_type' => '生物设定',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '黑道',
                        'is_bianyuan' => true,
                        'tag_type' => '职业范围',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '性转',
                        'is_bianyuan' => true,
                        'tag_type' => '特殊元素',
                    ]);
                }
                {
                    DB::table('tags')->insert([
                        'tag_name' => '短篇',
                        'tag_type' => '篇幅',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '中篇',
                        'tag_type' => '篇幅',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '长篇',
                        'tag_type' => '篇幅',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '大纲',
                        'tag_type' => '篇幅',
                    ]);
                }
                {
                    DB::table('tags')->insert([
                        'tag_name' => '连载',
                        'tag_type' => '进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '完结',
                        'tag_type' => '进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '暂停',
                        'tag_type' => '进度',
                    ]);
                }
                {
                    DB::table('tags')->insert([
                        'tag_name' => 'BL',
                        'tag_type' => '性向',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => 'GL',
                        'tag_type' => '性向',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => 'BG',
                        'tag_type' => '性向',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => 'GB',
                        'tag_type' => '性向',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '混合性向',
                        'tag_type' => '性向',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '无CP',
                        'tag_type' => '性向',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '其他性向',
                        'tag_type' => '性向',
                    ]);
                }
                {
                    DB::table('tags')->insert([
                        'tag_name' => '高H',
                        'is_bianyuan' => true,
                        'tag_type' => '床戏性质'
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '荤素均衡',
                        'is_bianyuan' => false,
                        'tag_type' => '床戏性质'
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '肉渣',
                        'is_bianyuan' => false,
                        'tag_type' => '床戏性质'
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '清水',
                        'is_bianyuan' => false,
                        'tag_type' => '床戏性质'
                    ]);
                }
                {
                    DB::table('tags')->insert([
                        'tag_name' => '长推',
                        'tag_type' => '编推',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '短推',
                        'tag_type' => '编推',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '汇总',
                        'tag_type' => '编推',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '高亮',
                        'tag_type' => '管理',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '置顶',
                        'tag_type' => '管理',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '精华',
                        'tag_type' => '管理',
                    ]);

                }
                {
                    DB::table('tags')->insert([
                        'tag_name' => '想读',
                        'tag_type' => '阅读进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '正在读',
                        'tag_type' => '阅读进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '养肥',
                        'tag_type' => '阅读进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '弃文',
                        'tag_type' => '阅读进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '已读',
                        'tag_type' => '阅读进度',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '多次阅读',
                        'tag_type' => '阅读反馈',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '推荐',
                        'tag_type' => '阅读反馈',
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
