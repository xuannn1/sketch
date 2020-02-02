import * as React from 'react';
import { classnames } from '../../../utils/classname';
import './expandable-message.scss';
import { Card } from '../common/card';

interface Props {
  title:React.ReactNode;
  footer?:string;
  boldTitle?:boolean;
  content:string;
  arrow?:boolean;
  className?:string;
}
interface State {
  expanded:boolean;
}

export class ExpandableMessage extends React.Component<Props, State> {
  public state:State = {
    expanded: false,
  };

  public toggled = false;
  public toggle = () => {
    this.toggled = true;
    this.setState((prevState) => {
      const expanded = !prevState.expanded;
      return { expanded };
    });
  }

  public render () {
    // let contentCln = 'accordion-content';
    // if (this.toggled) {
    //   contentCln = classnames(contentCln, `animate${this.state.expanded ? 'In' : 'Out'}`);
    //   this.toggled = false;
    // this.props.content
    console.log(this.props.content);
    const content = this.props.content.split ('\n').map ((line, i) => <p key={i}>{line}</p>); // otherwise the '\n' in string will be ignored in <p/>

    return <div className={classnames('expandable-message', this.props.className)} onClick={this.toggle}>
      <div className="expandable-message-title" onClick={this.toggle}>
        {this.props.arrow && <div className="arrow">
          <i className={classnames('fas', `fa-angle-${this.state.expanded ? 'up' : 'down'}`)}></i>
        </div>}
        <span className="expandable-message-title-text">{this.props.title}</span>
        <span className="icon">
          <i className="fas fa-caret-up"></i>
        </span>
      </div>
      <div className="expandable-message-content">
        {content}
        {this.props.footer && (<div className="expandable-message-footer"><span>{this.props.footer}</span></div>)}
      </div>
    </div>;
  }
}