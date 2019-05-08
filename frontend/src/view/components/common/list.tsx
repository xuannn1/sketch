import * as React from 'react';
import { classnames } from '../../../utils/classname';
import './list.scss';

class ListItem extends React.Component<{
  //props
  children:React.ReactNode;
  className?:string;
  arrow?:boolean;
  onClick?:() => void;
}, {
  //state
}> {
  public render () {
    return <div className={classnames('list-item', this.props.className)}
      onClick={this.props.onClick}>
      {this.props.arrow && <div className="list-arrow">
        <i className="fas fa-angle-right"></i>
      </div>}
      {this.props.children}
    </div>;
  }
}

export class List extends React.Component<{
  //props
  children:React.ReactNode;
  className?:string;
}, {
  //state
}> {
  public static Item = ListItem; 

  public render () {
    return <div className={classnames('list', this.props.className)}>
      {this.props.children}
    </div>;
  }
}