import { TopMenu } from "../../components/common";
import * as React from 'react';

export function CollectionNav () {
    return <TopMenu
        items={[
            {to:'/collection/book', label: '文章'},
            {to:'/collection/thread', label: '讨论'},
            {to:'/collection/list', label: '收藏单'},
        ]}
    />;
}