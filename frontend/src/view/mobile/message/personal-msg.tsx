import * as React from 'react';
import { API, ResData, ReqData } from '../../../config/api';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common/page';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';
import { Badge } from '../../components/common/badge';
import { List } from '../../components/common/list';
import { pageStyle, largeListItemStyle, badgeStyle, topCardStle, contentCardStyle, replyNotificationCardStyle, replyMessageContentStyle, unreadStyle, oneLineTruncationStyle } from './styles';

interface State {
  messageData:API.Get['/user/$0/message'];
  publicNoticeData:API.Get['/publicnotice'];
}

// TODO: 管理通知: waiting for API
// TODO: reduce API use by saving messages in localStorage
// TODO: implement mark as read
// TODO: we need a way to notify user API errors. (e.g. probably with a pop up)
// TODO: detect read public notice

export class PersonalMessage extends React.Component<MobileRouteProps, State> {
  public state:State = {
    messageData:{
      messages: [],
      paginate: ResData.allocThreadPaginate(),
      style: ReqData.Message.style.receiveBox,
    },
    publicNoticeData:{
      public_notices: [],
    }
  };

  public async componentDidMount() {
    const query = {withStyle: ReqData.Message.style.receiveBox};
    const fetchMsgData = this.props.core.db.getMessages(query).catch((e) => { console.log(e);
                                                                              return this.state.messageData; });
    const fetchPublicNotice = this.props.core.db.getPublicNotice().catch((e) => { console.log(e);
                                                                                  return this.state.publicNoticeData; });
    const [messageData, publicNoticeData] = await Promise.all([fetchMsgData, fetchPublicNotice]);
    this.setState({messageData, publicNoticeData});
    console.log(messageData, publicNoticeData);
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
            <List.Item onClick={this.onClickPublicNotice} arrow={true} style={largeListItemStyle}>
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

  // redirect user to public notice page
  private onClickPublicNotice = () => {
    // TODO: clear all unread notice
    this.props.core.history.push(`/messages/publicnotice`, {publicNoticeData: this.state.publicNoticeData});
  }
  /** ===========            user messages           =============== **/
  private getDialogues() : ResData.Message[] {
    const { messages } = this.state.messageData;
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
  private onClickDialogue = (chatWithID:number, chatWithName:string) => () => {
    this.props.core.history.push(`/messages/pm/${chatWithID}`, {chatWithName});
  }

  private renderMessages () {
    const dialogues = this.getDialogues();
    const renderDialogue = (dialogue:ResData.Message) => {
      const posterName:string = dialogue.poster ? dialogue.poster.attributes.name : '';
      const posterID:number = dialogue.attributes.poster_id;
      const seen:boolean = dialogue.attributes.seen;
      const content:string = dialogue.message_body ? dialogue.message_body.attributes.body : '';

      return (<List.Item key={dialogue.id} style={{background:'white', marginBottom:'0.3em'}} onClick={this.onClickDialogue(posterID, posterName)}>
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