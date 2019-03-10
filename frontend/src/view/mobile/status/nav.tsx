import { TopMenu } from "../../components/common";
import * as React from 'react';

export function StatusNav () {
    return <TopMenu
        items={[
            {to:'/status/collection', label: '关注'},
            {to:'/status/all', label: '全站'},
        ]}
    />;
}