<?php

return [
    //对于受限制的tag，用户最多选择x个
    'sum_limit_count' => 3,

    //所有大类列表
    'types' => [
        '大类' => true,
        '篇幅' => true,
        '性向' => true,
        '进度' => true,
        '同人原著' => true,
        '同人CP' => true,
        '同人聚类' => true,
        '版权' => true,
        '结局' => true,
        '故事气氛' => true,
        '整体时代' => true,
        '故事观感' => true,
        '强弱关系' => true,
        '伦理关系' => true,
        'CP关系' => true,
        '视角关系' => true,
        '人称' => true,
        '床戏性质' => true,
        '人物性格' => true,
        '执业范围' => true,
        '特殊元素' => true,
        '具体情节' => true,
        '世界设定' => true,
        '生物设定' => true,
        '风俗环境' => true,
        '性癖' => true,
        '编辑推荐' => true, //长推，短推，专题，
        '版主推荐' => true, //高亮，精华，置顶，
    ],
    'limits' => [
        'only_one' => [
            '篇幅' => true,
            '性向' => true,
            '进度' => true,
            '同人原著' => true,
            '同人CP' => true,
            '结局' => true,
        ],
        'sum_limit' => [
            '故事气氛' => true,
            '整体时代' => true,
            '故事观感' => true,
            '强弱关系' => true,
            '伦理关系' => true,
            'CP关系' => true,
            '视角关系' => true,
            '人称' => true,
            '床戏性质' => true,
            '人物性格' => true,
            '执业范围' => true,
            '特殊元素' => true,
            '具体情节' => true,
            '世界设定' => true,
            '生物设定' => true,
            '风俗环境' => true,
            '性癖' => true,
        ],
        'user_not_manageable' => [//用户没有选择权利
            '编辑推荐' => true, //
        ],
    ]
];
