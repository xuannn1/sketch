import * as React from 'react';

export function List (props:{
  children:React.ReactNode,
  className?:string;
}) {
  return <div className={props.className}>
  </div>;
}