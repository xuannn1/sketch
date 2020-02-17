import * as React from 'react';
import { API, ResData, ReqData } from '../../../config/api';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common/page';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { List } from '../../components/common/list';
import { RoutePath } from '../../../config/route-path';
import { MarkAllAsRead } from './mark-all-as-read';
import { Menu, MenuItem } from '../../components/common/menu';

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
    return (<Page className="msg-page"
        top={<NavBar goBack={this.props.core.route.back} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>

        < MarkAllAsRead />

        <Menu>
          <MenuItem icon="far fa-envelope icon" title="管理通知" badgeNum={1000}/>
          <MenuItem icon="far fa-envelope icon" title="公共通知"
            onClick={ this.onClickPublicNotice } badgeNum={1}/>
        </Menu>
        { this.renderMessages() }
      </Page>);
  }

  // redirect user to public notice page
  private onClickPublicNotice = () => {
    // TODO: clear all unread notice
    this.props.core.history.push(RoutePath.publicNotice, {publicNoticeData: this.state.publicNoticeData});
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
    const url = RoutePath.dialogue.replace(/:uid/, String(chatWithID));
    this.props.core.history.push(url, {chatWithName});
  }

  private renderMessages () {
    const dialogues = this.getDialogues();

    const renderDialogue = (dialogue:ResData.Message) => {
      const posterName:string = dialogue.poster ? dialogue.poster.attributes.name : '';
      const posterID:number = dialogue.attributes.poster_id;
      const seen:boolean = dialogue.attributes.seen;
      const content:string = dialogue.message_body ? dialogue.message_body.attributes.body : '';

      return (
        <List.Item key={dialogue.id} onClick={this.onClickDialogue(posterID, posterName)}>
          <div className="item-container">
            <div className="item-first-line">
              <div className={seen ? '' : 'unread'}>{posterName}</div>
            </div>
            {/* <div style={replyMessageContentStyle}> */}
            <div className="item-brief">
              <p className="one-line-truncation">{!seen ? <b>[有新消息]&nbsp;</b> : ''}{content}</p>
            </div>
          </div>
        </List.Item>);
    };

    return (
      <List>
        {dialogues.map((d) => renderDialogue(d))}
      </List>
            );
  }
}