import * as React from 'react';
import { Link } from 'react-router-dom';
import { classnames } from '../../../utils/classname';

export class Card extends React.Component<{
  // props
  children?:React.ReactNode;
  style?:React.CSSProperties;
  className?:string;
  ref?:(el:HTMLDivElement|null) => void;
  title?:string|{text:string, link:string};
}, {
  // state
  }> {
    public render () {
      return <div className={classnames('card', this.props.className)}
        ref={(el) => this.props.ref && this.props.ref(el)}
        style={Object.assign(
          {
            marginTop: '10px',
            padding: '10px',
            position: 'relative',
          },
          this.props.style || {})}>
          {this.props.title && (typeof this.props.title !== 'string' ?
        <Link to={this.props.title.link} className="title">{this.props.title.text}</Link> :
        <span className="title">{this.props.title}</span>)}
        {this.props.children}
      </div>;
    }
}