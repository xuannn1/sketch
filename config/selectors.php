<?php

return [
    'post_filter' => [
        'withType' => [
            'post' => '回帖',
            'comment' => '回帖的回帖',
            'review' => '书评',
            'chapter' => '章节',
            'question' => '提问',
            'answer' => '回答',
        ],
        'withComponent' => [
            'component_only' => '显示正文',
            'none_component_only' => '显示非正文',
        ],

        'ordered' => [
            'latest_created' => '最新发布',
            'most_replied' => '最多回复',
            'most_upvoted' => '最高赞',
            'random' => '随机乱序',
            'latest_responded' => '最新被回复',
            'default' => '时间顺序',
        ],
    ],
    'book_filter' => [
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
];
