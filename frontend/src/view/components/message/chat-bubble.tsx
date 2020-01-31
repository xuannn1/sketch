import * as React from 'react';
import './chat-bubble.scss';
import { classnames } from '../../../utils/classname';

export class ChatBubble extends React.Component<{
  // props
  fromMe:boolean;
  content:string;
  style?:React.CSSProperties;
  className?:string;
}, {
  // state
}> {

  public render () {
    const style = this.props.fromMe ? 'talk-bubble from-me-color from-me' : 'talk-bubble from-other-color from-other';
    const content = this.props.content.split ('\n').map ((line, i) => <p key={i}>{line}</p>); // otherwise the '\n' in string will be ignored in <p/>
    return (
      <div className={style}>
        <div className="talktext">
          {content}
        </div>
      </div>);

  }
}