<?php

return [
   'longcomment_length' => 200, //“长评”必须达到该字数
   'update_min' => 1000, //章节更新必须达到这个水平才能进入排名榜
   'default_user_group' => 10,
   'online_count_interval' => 15, //统计在线时间时，间隔多少分钟的时间算一次。
   'online_interval' => 30, //判断某用户在线的间隔。
   'monthly_email_resets' => 5, //一个月能修改多少次邮箱
   'default_majia' => '匿名咸鱼',
   'quiz_test_number' => 5, //目前每次测试取5道题
   'box_channel_id' => 14,
   'list_channel_id' => 13,
   'rewards' => [
       'shengfan' => '剩饭',
       'xianyu' => '咸鱼',
       'sangdian' => '丧点',
       'jifen' => '积分',
       'salt' => '盐粒',
       'fish' => '咸鱼',
       'ham' => '火腿',
   ],
   'votes' => [
       'upvote' => '赞',
       'downvote' => '踩',
       'funnyvote' => '搞笑',
       'foldvote' => '折叠',
   ],
   'activities' => [
      '1' => '回复了你',
      '2' => '回复帖子', //已作废
      '3' => '点评帖子', //已作废
      '4' => '点评点评', //已作废
      '5' => '赞了帖子',//已作废
      '6' => '有人提问', //已作废
      '7' => '打赏', //已作废
	  '8' => '被人圈' //还没做
   ],
   'token_level' => [
       0 => [
         'activated' => false,
         'level' =>0,
         'fish' => 0,
         'salt' => 0,
         'ham' => 0,
       ],
       2 => [
         'activated' => false,
         'level' =>2,
         'fish' => 25,
         'salt' => 100,
         'ham' => 0,
       ],
       7 => [
         'activated' => false,
         'level' =>7,
         'fish' => 5000,
         'salt' => 10000,
         'ham' => 200,
       ],
   ]
];
