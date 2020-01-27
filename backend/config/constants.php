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
    'box_channel_id' => 14,// 问题箱板块id
    'list_channel_id' => 13,// 清单板块id
    'commentary_channel_id' => 4,// 读写交流板块id
    'homework_channel_id' => 3,// 作业区板块id
    'deletion_grace_days' => 7,// 删除预计时间
    'apply_to_delete_prefix' => '申请删除',
    'invitation_token_prefix' => 'SOSAD_',
    'apply_to_cancel_delete_prefix' => '申请撤销删除',
    'application_cooldown_days' => 7, // 申请结果在7天内公示
    'robot_account_id' => 101, //机器人账户id
    'registration_quiz_total' => 11, //注册答题题目数量
    'registration_quiz_correct' => 7, // 注册答题正确情况
    'delay_count_model_interval' => 30, // 站内延迟统计数字的时间(分钟)，比如说阅读量每20分钟更新一次 TODO
    'delay_record_history_count_interval' => 500, //每多少条历史记录，向数据库更新一次 TODO
    'delay_record_history_count_interval_long' => 50, //每多少条历史记录，向数据库更新一次
    'min_batch_count_model_value' => 1,// 在更新数小于这个数值的时候，累加而不是直接更新模型 TODO
    'show_reviews_with_characters_over' => 20,
    'review_short' => 5, // 短评下限
    'review_medium' => 50, // 中评下限
    'review_long' => 200, // 长评下限
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
    'roles' => [ // 备份：目前有四种身份
        'admin',
        'editor',
        'senior',
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
    'new_user_base' => [
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
            'fish' => 10,
            'salt' => 50,
            'ham' => 0,
        ],
        7 => [
            'activated' => false,
            'level' =>7,
            'fish' => 5000,
            'salt' => 10000,
            'ham' => 200,
        ],
    ],
    'post_types' => [
        'post' => '回帖', // any
        'comment' => '点评', // any
        'volumn' => '卷', // TODO any

        'critique' => '作业批评', // homework
        'work' => '作业正文', // homework

        'question' => '问题', // box
        'answer' => '回答', // box

        'review' => '评荐', // list

        'chapter' => '章节', // book

        'essay' => '文章', // TODO column

        'case' => '情况',// TODO report
        'solution' => '处理结果', // TODO report
    ],
    'with_info_component_types' => [
        'chapter','volumn','review','answer','essay','solution'
    ],
    'owner_component_types' => [
        'chapter','volumn','review','answer','essay','work','solution'
    ],
    'all_post_types' => [
        'post','comment','chapter','volumn','review','question','answer','essay','work','critique','solution'
    ],
    'quiz_types' => [
        'level_up' => '站内升级测试题',
        'register' => '注册申请选择题',
        'essay' => '注册申请简答题',
    ],
    'task_titles' => [
        '2019winter' => 61,
    ],
];
