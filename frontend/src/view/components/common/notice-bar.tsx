import * as React from 'react';

interface Props {
  icon?:string;
  text:string;
  closeable?:boolean;
}
interface State {
}

export class NoticeBar extends React.Component<Props, State> {
    public render () {
        return <div className="notification is-danger">
        {this.props.closeable &&
          <button className="delete"></button> 
        }
        {this.props.text}
    </div>;
    }
}