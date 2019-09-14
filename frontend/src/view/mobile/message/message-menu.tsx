import * as React from 'react';
import { RouteMenu } from '../../components/common/route-menu';

export function MessageMenu () {
  const items = [
    {to:'/messages', label: '提醒'},
    {to:'/messages/pm', label: '个人消息'},
  ];
  let onIndex = 0;
  for (let i = 0; i < items.length; i ++) {
    if (location.pathname === items[i].to) {
      onIndex = i;
    }
  }
  return <RouteMenu
    items={items}
    onIndex={onIndex}
  />;
}