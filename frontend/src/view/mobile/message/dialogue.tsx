import * as React from 'react';
import { API, ResData, ReqData } from '../../../config/api';
import { MobileRouteProps } from '../router';
// import { StatusNav } from './nav';
import { Page } from '../../components/common/page';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';
import { Badge } from '../../components/common/badge';
import { List } from '../../components/common/list';
import { ChatBubble } from '../../components/message/chat-bubble';
import { pageStyle, DialogueCardStyle, posterNameStyle, messageStyle, myPosterNameStyle } from './styles';
import { Message } from '.';

interface State {
  data:API.Get['/user/$0/message'];
}

export class Dialogue extends React.Component<MobileRouteProps, State> {
  public state:State = {
    data:{
      messages: [],
      paginate: ResData.allocThreadPaginate(),
      style: ReqData.Message.style.dialogue,
    }
  };

  public async componentDidMount() {
    try {
      const query = {withStyle: ReqData.Message.style.dialogue, chatWith: this.props.match.params.uid};
      const data = await this.props.core.db.getMessages(query);
      this.setState({data});
      console.log(data);
    } catch (e) {
      console.log(e);
    }
  }

  public render () {
    // console.log(this.state.chatWithName);
    return (<Page style={pageStyle}
        top={<NavBar goBack={this.props.core.history.goBack} onMenuClick={() => console.log('open setting')}>
          {this.props.location.state.chatWithName}
        </NavBar>}>
        { this.renderMessages() }
      </Page>);
  }

  private renderMessages () : JSX.Element {
    const { messages } = this.state.data;
    return (<Card style={DialogueCardStyle}>
              {messages.map((m) => this.renderMessage(m))}
            </Card>);
  }

  private renderMessage (m:ResData.Message) : JSX.Element {
    const myID:number = this.props.core.user.id;
    const fromMe:boolean = myID == m.attributes.poster_id;
    const posterName:string = fromMe ? '我' : m.poster ? m.poster.attributes.name : ' ';
    const content:string = m.message_body ? m.message_body.attributes.body : '';
    return (<div key={m.id} style={messageStyle}>
              <h5 style={fromMe ? {...posterNameStyle, ...myPosterNameStyle} : posterNameStyle}>{posterName}</h5>
              <ChatBubble fromMe={fromMe} content={content}></ChatBubble>
            </div>);
  }
}