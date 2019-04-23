import * as React from 'react';
import { Link } from 'react-router-dom';
import './slider.scss';

export function Slider (props: {
  children: React.ReactNode[],
  morePath: string,
}) {
  return <div className="slider-wrapper">
      <div className="slider-list">
          {props.children.map((child,idx) => {
              return <div className="item" key={idx}>
                  {child}
              </div>
          })}
          <div className="item">
              <Link className="more" to={props.morePath}>
                  更多
              </Link>
          </div>
      </div>
  </div>
}