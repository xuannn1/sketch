<?php

return [
   'items_per_page' => 10,//当一页只看某个内容，显示多少内容
   'items_per_part' => 5,//当一个分区只看一个内容，显示多少内容
   'index_per_page' => 20,//当只搜索目录信息的时候，一页显示多少项目
   'index_per_part' => 3,//当index用于整合页面的时候，一个分区显示多少项目
   'comments_per_post' => 3,//每个post，显示最早的n-1条回复
   'update_min' => 1000, //章节更新必须达到这个水平才能进入排名榜
   'longcomment_lenth' => 200, //“长评”必须达到该字数
   'default_user_group' => 10,
   'default_majia' => '匿名咸鱼',

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
         'experience_points' => 50,
      ],
      2 => [//可以发无限私信给好友，每日发user_level个私信给陌生人
         'experience_points' => 100,
      ],
      3 => [//可以关联账户
         'experience_points' => 150,
         'xianyu' => 25,
      ],
      4 => [//可以悬赏（未做）
         'experience_points' => 300,
         'xianyu' => 30,
         'sangdian' => 10,
      ],
      5 => [//可以按扣除咸鱼／丧点的方式发私信给陌生人（未做）
         'experience_points' => 500,
         'xianyu' => 50,
         'sangdian' => 15,
      ],

   ],
];
