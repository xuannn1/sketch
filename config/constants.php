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
];
