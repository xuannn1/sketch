import * as React from 'react';
import { Link } from 'react-router-dom';

export function Card (props:{
  children?:React.ReactNode;
  style?:React.CSSProperties;
  className?:string;
  ref?:(el:HTMLDivElement|null) => void;
  title?:string|{text:string, link:string};
}) {
  return <div className={`card ${props.className || ''}`}
      ref={(el) => props.ref && props.ref(el)}
      style={Object.assign({
          marginTop: '10px',
          padding: '10px',
          position: 'relative',
      }, props.style || {})}>
      {props.title && (typeof props.title !== 'string' ? 
          <Link to={props.title.link} className="title">{props.title.text}</Link> : 
          <span className="title">{props.title}</span>)}
      {props.children}
  </div>;
}