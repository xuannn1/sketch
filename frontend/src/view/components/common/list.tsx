import * as React from 'react';

export function List (props:{
  children:React.ReactNode[],
  className?:string;
}) {
  return <div className={props.className}>
    {props.children.map((child, idx) => {
      return <div style={{}} key={idx}>
        {idx !== 0 && <hr />}
        {child}
      </div>;
    })}
  </div>;
}