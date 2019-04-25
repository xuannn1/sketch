import * as React from 'react';
import { Menu } from "../../components/common/menu";

export function StatusNav () {
  return <Menu
    items={[
      {to:'/status/collection', label: '关注'},
      {to:'/status/all', label: '全站'},
    ]}
  />;
}