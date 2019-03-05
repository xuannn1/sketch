import * as React from 'react';
import { TopMenu } from "../../components/common";

export function HomeTopNav () {
    return <TopMenu
        items={[
            {to:'/', label: '首页'},
            {to:'/homebook', label: '文库'},
            {to:'/homethread', label: '论坛'}, 
        ]}
    />;
}