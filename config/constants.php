<?php

return [
   'items_per_page' => 10,//当一页只看某个内容，显示多少内容
   'items_per_part' => 5,//当一个分区只看一个内容，显示多少内容
   'index_per_page' => 20,//当只搜索目录信息的时候，一页显示多少项目
   'index_per_part' => 3,//当index用于整合页面的时候，一个分区显示多少项目
   'comments_per_post' => 3,//每个post，显示最早的n-1条回复
   'update_min' => 1000, //章节更新必须达到这个水平才能进入排名榜
   'longcomment_lenth' => 200, //“长评”必须达到该字数

   'book_info' =>[
      'originality_info' => [
         0 => '同人',
         1 => '原创',
      ],
      'book_lenth_info' => [
         '1' => '短篇',
         '2' => '中篇',
         '3' => '长篇',
      ],
      'book_status_info' => [
         '1' => '连载',
         '2' => '完结',
         '3' => '暂停',
      ],
      'sexual_orientation_info' => [ //0:未知，1:BL，2:GL，3:BG，4:GB，5:混合性向，6:无CP，7:其他性向
        '0' => '性向未知',
        '1' => 'BL',
        '2' => 'GL',
        '3' => 'BG',
        '4' => 'GB',
        '5' => '混合性向',
        '6' => '无CP',
        '7' => '其他性向',
      ],
   ],
   'administrations' => [
      1 => '锁帖主题',
      2 => '解锁主题',
      3 => '转为私密主题',
      4 => '转为公开主题',
      5 => '删帖主题',
      6 => '恢复主题',
      7 => '删除回帖',
      8 => '删除点评',
      9 => '转移板块',
      10 => '修改马甲',
      11 => '折叠帖子',
      12 => '解折帖子',
      13 => '禁言用户',
      14 => '解禁用户',
   ],
   'activities' => [
      '1' => '回复主题',
      '2' => '回复帖子',
      '3' => '点评帖子',
   ],
   'level_up' => [
      1 => [//基础功能,可以开始下载
         'jifen' => 50,
      ],
      2 => [//可以发无限私信给好友，每日发user_level个私信给陌生人
         'jifen' => 100,
      ],
      3 => [//可以关联账户
         'jifen' => 150,
         'xianyu' => 25,
      ],
      4 => [//可以悬赏（未做）
         'jifen' => 300,
         'xianyu' => 30,
         'sangdian' => 10,
      ],
      5 => [//可以按扣除咸鱼／丧点的方式发私信给陌生人（未做）
         'jifen' => 500,
         'xianyu' => 50,
         'sangdian' => 15,
      ],

   ],
   'webinfo_about' => '
   大家好，欢迎来废文网玩。


请把这里当作一个，自由存文，自由交流感想的地方吧。


本站的基本结构融合了论坛、文库、评分、迷你微博系统，意图以此鼓励高质量的读写交流。


我们希望，作者都能找到自己的小天使，读者都能找到喜欢的文章，不同题材的文章百花齐放，优秀的评论得到分享和赞誉。


本站`禁人身攻击`，`禁人肉`，`禁抄袭`，`禁恋童`。


本站鼓励高质量的原创创作。在本站所发文章，版权归作者所有，本站不保留任何权益。转载需出具授权。请不要发布侵犯他人知识产权的文字。


本站鼓励友善、高质量的评论。在本站所发各类评论，默认按 _[知识共享(CC)-署名(BY)-非商业性(NC)-相同方式共享(SA)](https://creativecommons.org/licenses/by-nc-sa/3.0/deed.zh)_ 的方式发布，如果不想按此发布，请在回帖开头做好标注。


详细的版规，请以 _[关于废文网版规的详细说明](http://sosad.fun/threads/136)_ 的具体解释为准。


希望大家玩得开心，天天丧丧～
   ',//这是简介文件

   'webinfo_help' => [
     1 => [
       0 => '1.网站界面',
       1 => '
  1.1 账户
  注册： 本论坛目前采用邀请码注册制度。邀请码获取请关注相关宣传信息。
  忘记密码： 在登陆页面点选“忘记密码”，输入自己的邮箱，即可接收重置密码邮件。
  其他疑难，可以在版务管理专版跟帖，或者微博站长@文栈君咨询。

  1.2 积分，咸鱼，剩饭，丧点
		以上是本站特色的虚拟积分系统。发文、发帖、回帖、点评、签到、参加活动……都能增加这些积分。积分影响等级，等级影响到网站一小部分的功能使用，比如发送私信等。咸鱼和剩饭能够对作者进行奖励。本站大部分功能对全部用户开放，不需要对积分获取过于执着。

  1.3 顶部导航栏
		签到：在导航栏最顶，有一个红色的“我要签到”按键。签到有签到奖励，连续签到达到一定日期之后，奖励也会增加。
		文库：可以按照文章类型淘文的地方
		论坛：本站其他讨论，以及站内用户概况
		（登陆可见）收藏：
		（登陆可见）用户专区：管理个人资料，查看自己发布的所有文章、所有主题贴、所有长评。查看自己的粉丝与好友，查看自己获得的私信
		黄色提醒：黄色提醒是因为收到提示，说明有人回帖/点评/发送私信/点赞。点选“消息中心->清除”即可清理已有提醒。我们理解有的作者希望这个黄色一直闪烁的心情，做成这个样子。可以选择不接受陌生人私信/不接受点赞提醒，来免除打扰。

  1.4 首页格局
		题头：本站精神风貌的代表呈现部分
		小微博（边上有一个“发布”键）：可以发布当前心情。
		论坛主要板块：按序排布，后述。

  1.5 底部辅助页面
		帮助：就是您现在浏览的页面。
		关于：本站概况和相关原则。注册用户默认遵守这些规则，请务必仔细查看。
		Github：实时查看本站源代码。

       ',
     ],
     2 => [
       0 => ' 2.读者看文',
       1 => '

 2.1 文库
 		点击导航栏“文库”按钮即可进入文库淘文。上方筛选功能提供针对“原创性”、“性向”、“篇幅”、“进度”的多选符合筛选。
 		文库排序：目前按照最后更新章节时间倒序排列（此章节必须达到一定字数要求）。

 2.2 收藏
 		文章收藏：在对应文章主页点选“收藏”，就能收藏该文章。收藏后，文章“更新”将会发送提示。可以通过“收藏->整理”，来针对性管理每一篇文章的收藏情况。可以单独选择删除收藏，或者屏蔽对该文章的更新提醒。
 		主题贴收藏：主题贴收藏和文章收藏操作一致，点击“收藏”即可。主题贴收藏后，会提示后来对这一主题进行回帖的情况。
 		动态收藏：这里可以查看您所关注用户的小微博更新。

 2.3 论坛板块说明
 		原创：原创文章由此进入
 		同人：同人文章由此进入
 		（隐藏）作业专区：作业活动的地方
 		读写交流：和写文读文有关的心得体悟等
 		日常闲聊：水区，水水更健康
 		随笔：并非小说的作品
 		站务管理：各类通知，使用咨询
 		违规举报：小摩擦、小矛盾，一般情况下的违反版规，来这里
 		投诉仲裁：严重的事
 	`请大家仔细观察板块分区，尽量不要发错区。`

 2.4 看文互动
 		咸鱼：一种昂贵恶臭的虚拟物，扔咸鱼能顶帖。
 		剩饭：一种廉价广谱的虚拟物，没什么卵用。
 		收藏：将文章放入收藏夹。
 		回帖：顶帖，写下感受，让作者知道你的爱。
 		点评：在文案留下一句简单的感受，让大家都能看到。

       '
     ],
       3 => [
         0 => ' 3.作者发文',
         1 => '
 3.1 发文入口
  		从右上角“用户专区->我要发文“进入，按顺序点选填写相应信息即可发文。
  		发文时如有延迟，请勿`反复`点选“发布”键。
  		请区分`文案`和`正文`，正文请点击`发新章节`进行发布，不要放在文案内。标题和文案请符合规范。

 3.2 主题贴能做的操作
  		是否公开：如果不勾选，则帖子不对外开放，可以作为私下存文。管理员发现帖子标题等处不规范，也会将该项取消（也就是隐藏处理），作者修改好后可以自行公开。
  		是否允许跟帖：如果不勾选，别人只能看不能评。如果遇到文下争议，来不及举报，可以应急。
  		是否使用Markdown格式：只影响文案部分。

         '
     ],
     4 => [
       0 => ' 4.常见问题',
       1 => '
 4.1 什么是markdown格式，怎么使用？
      这是网站目前使用的格式系统。如果您的帖子需要”斜体“、”加粗“、显示高级格式，请在发帖时勾选”使用Markdown语法“，在对应的文本框里点击按钮应用格式，使用“预览”查看效果。请注意，markdown语法要求两个空格或者两个回车作为换行符号。如果对此语法不习惯，只要不勾选本项即可。

 4.2 丢失数据怎么办？
      用户登录后，所有大文本框下均有”恢复数据“按键，实时存储输入数据，点选即可。如果不小心误选，不要刷新页面，再点一次即可撤销。

 4.3 什么是段首缩进，怎么操作？
      勾选之后能够自动在段首空两格。不选的话，就不空格（缩进）。
       '
   ],
   ],//放帮助文件
];
