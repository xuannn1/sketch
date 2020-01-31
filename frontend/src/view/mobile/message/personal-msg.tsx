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
import { pageStyle, largeListItemStyle, badgeStyle, topCardStle, contentCardStyle, replyNotificationCardStyle, replyMessageContentStyle, unreadStyle, oneLineTruncationStyle } from './styles';
import { mockReplyNotifications } from './mock-data';
import { Message } from '.';
import { Dialogue } from './dialogue';

interface State {
  data:API.Get['/user/$0/message'];
}

// TODO: 管理通知, 公共通知: waiting for API
// TODO: reduce API use by saving messages in localStorage
// TODO: implement mark as read

export class PersonalMessage extends React.Component<MobileRouteProps, State> {
  public state:State = {
    data:{
      messages: [],
      paginate: ResData.allocThreadPaginate(),
      style: ReqData.Message.style.receiveBox,
    },
  };

  public async componentDidMount() {
    try {
      const query = {withStyle: ReqData.Message.style.receiveBox};
      const data = await this.props.core.db.getMessages(query);
      this.setState({data});
      console.log(data);
    } catch (e) {
      console.log(e);
    }
  }

  public render () {
    return (<Page style={pageStyle}
        top={<NavBar goBack={this.props.core.history.goBack} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>
        <Card style={topCardStle}>
          <a className="is-text" style={{color:'#d2646a'}}>全部标记已读</a>
        </Card>
        <Card style={contentCardStyle}>
          <List>
            <List.Item onClick={() => console.log('click item ')} arrow={true} style={largeListItemStyle}>
              <i className="far fa-envelope icon"></i>
              管理通知
              {/* <Badge num={1000} max={100} style={badgeStyle}/> */}
            </List.Item>
            <List.Item onClick={() => console.log('click item ')} arrow={true} style={largeListItemStyle}>
              <i className="far fa-envelope icon"></i>
              公共通知
              <Badge num={2} max={100} style={badgeStyle}/>
            </List.Item>
          </List>
        </Card>
        {/* reply notifications */}
        { this.renderMessages() }
      </Page>);
  }

  private getDialogues() : ResData.Message[] {
    const { messages } = this.state.data;
    const dialogues:{[key:string]:ResData.Message} = {};
    const dialoguesArray:ResData.Message[] = [];
    messages.forEach((m) => {
      if (!dialogues[m.attributes.poster_id]) {
        dialogues[m.attributes.poster_id] = m;
        dialoguesArray.push(m); // so the dialogues are perserved in time order
      }
    });
    return dialoguesArray;
  }

  // @param chatWithID - the id of user you are chating with
  // @param chatWithName - the name of user you are chating with
  private onClicDialogue = (chatWithID:number, chatWithName:string) => () => {
    this.props.core.history.push(`/messages/pm/${chatWithID}`, {chatWithName});
  }

  private renderMessages () {
    const dialogues = this.getDialogues();
    const renderDialogue = (dialogue:ResData.Message) => {
      const posterName:string = dialogue.poster ? dialogue.poster.attributes.name : '';
      const posterID:number = dialogue.attributes.poster_id;
      const seen:boolean = dialogue.attributes.seen;
      const content:string = dialogue.message_body ? dialogue.message_body.attributes.body : '';

      return (<List.Item key={dialogue.id} style={{background:'white', marginBottom:'0.3em'}} onClick={this.onClicDialogue(posterID, posterName)}>
                <h6 className="is-6" style={seen ? {} : unreadStyle}>{posterName}</h6>
                <div style={replyMessageContentStyle}>
                  <p style={oneLineTruncationStyle}>{!seen ? <React.Fragment><b>[有新消息]</b>{` `}</React.Fragment> : ''}{content}</p>
                </div>
              </List.Item>);
    };

    return (<Card style={replyNotificationCardStyle}>
              <List style={{background:'transparent'}}>
                {dialogues.map((d) => renderDialogue(d))}
              </List>
            </Card>);
  }
}