<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\Label;
use App\Models\Tag;
use Carbon;
use App\Models\InvitationToken;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      {//板块channels设定 0公开 10登录 20私密 30管理 --不同等级，公开程度不同
         {// state_10（文库相关）：原创-1、同人-2、随笔-3
            Channel::create([
               'channelname' => '原创',//Channel No.1
               'channel_state' => '1',
            ]);
            Channel::create([
               'channelname' => '同人',//Channel No.2
               'channel_state' => '1',
            ]);
            Channel::create([
               'channelname' => '随笔',//Channel No.3
               'channel_state' => '1',
            ]);
         }
         {// state_0（闲谈相关）：日常闲聊-4、读写交流-5
            Channel::create([
               'channelname' => '日常闲聊',//Channel No.4
               'channel_state' => '0',
            ]);
            Channel::create([
               'channelname' => '读写交流',//Channel No.5
               'channel_state' => '0',
            ]);
         }
         {// state_20（高级用户组）：短篇练习-6
            Channel::create([
               'channelname' => '短篇练习',//Channel No.9
               'channel_state' => '20',
            ]);
         }
         {// state_0 (版务相关）：站务管理-7、违规举报-8、投诉仲裁-9
            Channel::create([
               'channelname' => '站务管理',//Channel No.6
               'channel_state' => '0',
            ]);
            Channel::create([
               'channelname' => '违规举报',//Channel No.7
               'channel_state' => '0',
            ]);
            Channel::create([
               'channelname' => '投诉仲裁',//Channel No.8
               'channel_state' => '0',
            ]);
         }

         {// state_30（管理组）：管理-10、档案-11
            Channel::create([
               'channelname' => '管理',//Channel No.10
               'channel_state' => '30',
            ]);
            Channel::create([
               'channelname' => '档案',//Channel No.11
               'channel_state' => '30',
            ]);
         }
      }

      {//预设labels
         {//（板块：日常闲聊 Channel No.4） 闲谈、吐槽、求助、八卦、安利
            label::create([
               'labelname' => '闲谈',
               'channel_id' => '4',
            ]);
            label::create([
               'labelname' => '吐槽',
               'channel_id' => '4',
            ]);
            label::create([
               'labelname' => '求助',
               'channel_id' => '4',
            ]);
            label::create([
               'labelname' => '八卦',
               'channel_id' => '4',
            ]);
            label::create([
               'labelname' => '安利',
               'channel_id' => '4',
            ]);
         }
         {//11（板块：读写交流 Channel No.5） 分享、探讨、评文、自荐、推文
            label::create([
               'labelname' => '分享',
               'channel_id' => '5',
            ]);
            label::create([
               'labelname' => '探讨',
               'channel_id' => '5',
            ]);
            label::create([
               'labelname' => '评文',
               'channel_id' => '5',
            ]);
            label::create([
               'labelname' => '自荐',
               'channel_id' => '5',
            ]);
            label::create([
               'labelname' => '推文',
               'channel_id' => '5',
            ]);
            label::create([
               'labelname' => '作业',
               'channel_id' => '5',
            ]);
            label::create([
               'labelname' => '活动',
               'channel_id' => '5',
            ]);
         }
         {//12（板块：短篇练习 Channel No.6）往期、本次
            label::create([
               'labelname' => '往期',
               'channel_id' => '6',
            ]);
            label::create([
               'labelname' => '本次',
               'channel_id' => '6',
            ]);
         }
         {//21（板块：站务管理 Channel No.7）账号、建议意见、bug汇报、站务公告
            label::create([
               'labelname' => '账号',
               'channel_id' => '7',
            ]);
            label::create([
               'labelname' => '建议意见',
               'channel_id' => '7',
            ]);
            label::create([
               'labelname' => 'bug汇报',
               'channel_id' => '7',
            ]);
            label::create([
               'labelname' => '站务公告',
               'channel_id' => '7',
            ]);
         }
         {//22（板块：违规举报 Channel No.8）人身攻击、信息泄露、待处理、处理中、历史记录
            label::create([
               'labelname' => '人身攻击',
               'channel_id' => '8',
            ]);
            label::create([
               'labelname' => '信息泄露',
               'channel_id' => '8',
            ]);
            label::create([
               'labelname' => '待处理',
               'channel_id' => '8',
            ]);
            label::create([
               'labelname' => '处理中',
               'channel_id' => '8',
            ]);
            label::create([
               'labelname' => '历史记录',
               'channel_id' => '8',
            ]);
         }
         {//23（板块：投诉仲裁 Channel No.9）管理投诉、权益仲裁、待处理、处理中、历史记录
            label::create([
               'labelname' => '管理投诉',
               'channel_id' => '9',
            ]);
            label::create([
               'labelname' => '权益仲裁',
               'channel_id' => '9',
            ]);
            label::create([
               'labelname' => '待处理',
               'channel_id' => '9',
            ]);
            label::create([
               'labelname' => '处理中',
               'channel_id' => '9',
            ]);
            label::create([
               'labelname' => '历史记录',
               'channel_id' => '9',
            ]);
         }
         {//31 （板块：管理组 Channel No.10) 处理投诉、日常管理、仲裁相关
            label::create([
               'labelname' => '处理投诉',
               'channel_id' => '10',
            ]);
            label::create([
               'labelname' => '日常管理',
               'channel_id' => '10',
            ]);
            label::create([
               'labelname' => '仲裁相关',
               'channel_id' => '10',
            ]);
         }
         {//32 （板块：管理组 Channel No.10) 处理投诉、日常管理、仲裁相关
            label::create([
               'labelname' => '建站相关',
               'channel_id' => '11',
            ]);
            label::create([
               'labelname' => '2017',
               'channel_id' => '10',
            ]);
         }
         {//文库相关label预设 （原创大类 同人大类）
            {//（原创大类 Channel No.1）古代 现代 民国 西方 奇幻 科幻 灵异 玄幻 网游 其他
               label::create([
                  'labelname' => '古代',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '现代',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '民国',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '西方',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '奇幻',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '科幻',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '灵异',
                  'channel_id' => '1',
               ]);
               label::create([
                  'labelname' => '玄幻',
                  'channel_id' => '1',
               ]);

               label::create([
                  'labelname' => '其他',
                  'channel_id' => '1',
               ]);

            }
            {//（同人大类 Channel No.2）影视、动漫、游戏、小说、真人、其他
               label::create([
                  'labelname' => '影视',
                  'channel_id' => '2',
               ]);
               label::create([
                  'labelname' => '动漫',
                  'channel_id' => '2',
               ]);
               label::create([
                  'labelname' => '游戏',
                  'channel_id' => '2',
               ]);
               label::create([
                  'labelname' => '小说',
                  'channel_id' => '2',
               ]);
               label::create([
                  'labelname' => '真人',
                  'channel_id' => '2',
               ]);
               label::create([
                  'labelname' => '其他',
                  'channel_id' => '2',
               ]);
            }

            //通用tag-0
            //
            //边缘tag-5
            //同人原著题材-10
            //同人cp-20

            {//（萌梗）强强、破镜重圆、1v1、狗血、哨兵向导、娱乐圈
               tag::create([
                  'tagname' => '强强',
                  'tag_group' => '0',
               ]);
               tag::create([
                  'tagname' => '破镜重圆',
                  'tag_group' => '0',
               ]);
               tag::create([
                  'tagname' => '1v1',
                  'tag_group' => '0',
               ]);
               tag::create([
                  'tagname' => '狗血',
                  'tag_group' => '0',
               ]);
               tag::create([
                  'tagname' => '哨兵向导',
                  'tag_group' => '0',
               ]);
               tag::create([
                  'tagname' => '娱乐圈',
                  'tag_group' => '0',
               ]);
            }
            {//（同人原著）-2
               tag::create([
                  'tagname' => '全职高手',
                  'tag_group' => '10',
               ]);
            }
            {//（同人cp）-3
               tag::create([
                  'tagname' => '周叶',
                  'tag_group' => '20',
               ]);
            }
            {//（边缘）-5 文章含肉超过20%，或题材包含人兽、触手、父子、乱伦、生子、产乳、abo、军政、黑道、性转
               tag::create([
                  'tagname' => '高H',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '人兽',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '触手',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '父子',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '乱伦',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '生子',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '产乳',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => 'abo',
                  'tag_group' => '5',
                ]);
               tag::create([
                  'tagname' => '黑道',
                  'tag_group' => '5',
               ]);
               tag::create([
                  'tagname' => '性转',
                  'tag_group' => '5',
               ]);
            }


         }
      }
      InvitationToken::create([
          'user_id' => 1,
          'token' => 'SOSAD_invite',
          'invitation_times' => 10,
          'invite_until' => Carbon::now()->addYears(2),
      ]);
   }
}




//元老：权限30，可以看到管理信息和档案情况
//管理员：admin 可以做各种实际操作
//超级管理员：管理员权限控制中心
