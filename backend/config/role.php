<?php
return [
    'roles' => [
        'senior-user' => [
            'can_see_homework' => true,
        ],
        'homeworker' => [
            'can_see_homework' => true,
        ],
        'editor' => [
            'can_see_homework' => true,
            'can_review_quotes' => true,
        ],
        'senior_editor' => [
            'can_see_homework' => true,
            'can_review_quotes' => true,
            'can_recommend' => true,
        ],
        'admin' => [
            'can_see_anything' => true,
            'can_review_anything' => true,
            'can_manage_anything' => true,
        ],
        
        'no_post' => [
            'can_not_post' => true,
        ],
        'no_login' => [
            'can_not_login' => true,
        ],
        'no_homework' => [
            'can_not_register_homework' => true,
        ],
    ],
    // 'level_permissions' => [
    //     'minimal_level_for_post' => 1,
    //     'minimal_level_for_write' => 1,
    // ],
    // //1:post to one's thread 2: post to one's post 3: comment to one's post 4: comment to one's comment; 5:upvote to one's post
    // 'level_up' => [
    //     1 => [//
    //         'experience_points' => 20,
    //         'xianyu' => 0,
    //         'sangdian' => 0,
    //     ],
    //     2 => [//可以下载图书（含回帖方式）
    //         'experience_points' => 50,
    //         'xianyu' => 10,
    //         'sangdian' => 0,
    //     ],
    //     3 => [//可以下载图书（脱水方式）
    //         'experience_points' => 100,
    //         'xianyu' => 25,
    //         'sangdian' => 0,
    //     ],
    //     4 => [//
    //         'experience_points' => 300,
    //         'xianyu' => 30,
    //         'sangdian' => 10,
    //     ],
    //     5 => [//可以按扣除咸鱼／丧点的方式发私信给陌生人（未做）
    //         'experience_points' => 500,
    //         'xianyu' => 50,
    //         'sangdian' => 15,
    //     ],
    //     6 => [//可以按扣除咸鱼／丧点的方式发私信给陌生人（未做）
    //         'experience_points' => 700,
    //         'xianyu' => 100,
    //         'sangdian' => 20,
    //     ],
    //     7 => [//可以按扣除咸鱼／丧点的方式发私信给陌生人（未做）
    //         'experience_points' => 1000,
    //         'xianyu' => 50,
    //         'sangdian' => 25,
    //     ],
    // ],
];
