import * as React from 'react';

export function Popup (props:{
  children:React.ReactNode;
  position?:'bottom'|'center';
  width?:number;
  height?:number;
  style?:React.CSSProperties;
  className?:string;
}) {
  return <div>{props.children}</div>
}