import { Link } from 'react-router-dom';
import * as React from 'react';
import './channel-preview.scss';

interface Props {
  channel:{id:number, name:string};
  threads:{id:number, channel_id:number, title:string, brief:string, author:string }[];
}
interface State {
}

export class ChannelPreview extends React.Component<Props, State> {
  public render() {
    return <div style={{
      display:'flex',
      flexDirection:'column',
      alignItems: 'center',
      margin:'0',
      padding:'0',
      width:'100%'}} className="channelpreview">
      <div style={{
        margin:'0',
        backgroundColor:'rgba(244,245,249,1)',
        padding:'10px 20px',
        width:'100%',
        textAlign:'left',
        fontSize:'1.1rem',
        fontWeight:'bold'}}>
        <Link key={this.props.channel.id}
                to={`/threads/?channels=[${this.props.channel.id}]`}>
              {this.props.channel.name}
        </Link>
      </div>

      {this.props.threads.map((thread, id) => {
        const borderBottomConst= id < (this.props.threads.length - 1 ) ? '4px solid rgba(244,245,249,1)' : '';
        return <div style={{
          margin:'0px',
          backgroundColor:'white',
          padding:'10px 0',
          width:'100%',
          display:'flex',
          flexDirection:'column',
          justifyContent:'flex-start',
          borderBottom: borderBottomConst }}>
        <div style={{margin:'0 20px'}}>
            <div style={{
              margin:'0px',
              fontSize:'1rem',
              float:'left',
              fontWeight:'bold'}}>
              <Link className="" key={thread.id}
                to={`/thread/${thread.id}`}>{thread.title}
              </Link>
            </div>
            <div style={{
              margin:'0px',
              padding:'0px',
              float:'right',
              textAlign:'right',
              width:'100px',
              fontSize:'0.9rem'}}>
              {thread.author}
            </div>
        </div>
        <div style={{margin:'0 20px', textAlign:'left', fontSize:'0.9rem'}}>{thread.brief}</div>
      </div> ;
      })}

    </div>;
  }
}