<?php
return [
    '200' => 'success',
    '400' => 'not found',
    '401' => 'unauthorised',//未登陆，或未能获得相关频道的发布授权，或不具有修改资格
    '403' => 'permission denied',
    '404' => 'not found',
    '409' => 'data conflict', //数据内容重复
    '422' => 'validation failed',//不符合规则的内容，
    '481' => 'classification data corruption', //分类性数据冲突，比如大类信息和频道信息不能对应匹配，或不能检索到对应的大类信息，或许可以考虑更新大类信息
    '488' => 'forbidden word',//内容中违禁词超过了运作能力（比如标题因违禁词存在变成空白字串）
];
