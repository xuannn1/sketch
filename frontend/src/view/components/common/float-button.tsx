import * as React from 'react';

type Button = {
  text?:string;
  icon?:string;
  onClick:() => void;
}

export function FloatButton (props:{
  content:Button|Button[];
}) {
  return <div></div>;
}