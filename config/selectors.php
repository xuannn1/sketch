<?php

return [
    'thread_filter' => [
        'withType' => [
            'post' => '回帖',
            'comment' => '点评',
        ],

        'withComponent' => [
            'no_comment' => '不显示点评',
        ],

        'ordered' => [
            'default' => '最早回复',
            'latest_created' => '最新发布',
            'most_replied' => '最多回复',
            'most_upvoted' => '最高赞',
            'random' => '随机乱序',
            'latest_responded' => '最新被回复',
        ],
    ],
    'book_filter' => [
        'withType' => [
            'chapter' => '章节',
            'post' => '回帖',
            'comment' => '点评',
        ],
        'withComponent' => [
            'component_only' => '只显示正文',
            'post_N_comment' => '回帖和点评',
            'no_comment' => '不显示点评',
        ],

        'ordered' => [
            'default' => '最早发布',
            'latest_created' => '最新发布',
            'most_replied' => '最多回复',
            'most_upvoted' => '最高赞',
            'random' => '随机乱序',
            'latest_responded' => '最新被回复',
        ],
    ],
    'list_filter' => [
        'withType' => [
            'post' => '回帖',
            'comment' => '点评',
            'review' => '书评',
        ],
        'withComponent' => [
            'component_only' => '只显示书评',
            'post_N_comment' => '回帖和点评',
            'no_comment' => '不显示点评',
        ],

        'ordered' => [
            'default' => '最早发布',
            'latest_created' => '最新发布',
            'most_replied' => '最多回复',
            'most_upvoted' => '最高赞',
            'random' => '随机乱序',
            'latest_responded' => '最新被回复',
        ],
    ],
    'box_filter' => [
        'withType' => [
            'post' => '回帖',
            'comment' => '点评',
            'question' => '提问',
            'answer' => '回答',
        ],
        'withComponent' => [
            'component_only' => '只显示问+答',
            'post_N_comment' => '回帖和点评',
            'no_comment' => '不显示点评',
        ],

        'ordered' => [
            'default' => '时间顺序',
            'latest_created' => '最新发布',
            'most_replied' => '最多回复',
            'most_upvoted' => '最高赞',
            'random' => '随机乱序',
            'latest_responded' => '最新被回复',
        ],
    ],
    'book_index_filter' => [
        'inChannel' => [
            '1' => '原创小说',
            '2' => '同人小说'
        ],
        'ordered' => [
            'default' => '最后回复',
            'latest_add_component' => '最新更新',
            'total_char' => '总字数',
            'jifen' => '总积分',
            'weighted_jifen' => '均字数积分',
            'latest_created' => '最新创建',
            'collection_count' => '最多收藏',
            'random' => '随机乱序',
        ],
    ],

    'collection_filter' => [
        'order_by' => [
            0 => '最新收藏',
            1 => '最新回复',
            2 => '最新章节',
            3 => '最新创立',
         ]
    ]

];
