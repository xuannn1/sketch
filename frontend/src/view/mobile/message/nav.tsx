import { TopMenu } from "../../components/common";
import * as React from 'react';

export function MessageNav () {
    return <TopMenu
        items={[
            {to:'/message/unread', label: '未读'},
            {to:'/message', label: '全部'},
            {to:'/message/list', label: '信箱'},
        ]}
    />;
}