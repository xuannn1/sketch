import * as React from 'react';
import { Menu, MenuPosition } from '../components/common/menu';

export function MainMenu () {
    const items = [
        {to:'/', label: 'home'},
        {to:'/status/all',label: 'status'},
        {to:'/collection/book', label: 'collection'},
        {to:'/user', label: 'user'},
    ];
    let onIndex = 0;
    for (let i = 0; i < items.length; i ++) {
        if (location.pathname === items[i].to) {
            onIndex = i;
        }
    }

    console.log(location.pathname)
    return <Menu
        onIndex={onIndex}
        items={items}
        position={MenuPosition.bottom}
    />
}