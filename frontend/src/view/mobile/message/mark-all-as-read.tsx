import * as React from 'react';
import { classnames } from '../../../utils/classname';

export function MarkAllAsRead (props:{
  className?:string;
}) {
  return (
    <div className="blank-block right-align">
      <a>全部标记已读</a>
    </div>);
}