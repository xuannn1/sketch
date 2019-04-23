import * as React from 'react';
import { Menu, MenuPosition } from '../../components/common/menu';

export function CollectionNav () {
    return <Menu
        position={MenuPosition.top}
        items={[
            {to:'/collection/book', label: '文章'},
            {to:'/collection/thread', label: '讨论'},
            {to:'/collection/list', label: '收藏单'},
        ]}
    />;
}