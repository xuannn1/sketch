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

interface State {
  data:API.Get['/user/$0/message'];
}

// TODO: 管理通知, 公共通知: waiting for API

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

  private renderMessages () {
    const dialogues = this.getDialogues();
    // TODO: mark msg as read
    return (<Card style={replyNotificationCardStyle}>
              <List style={{background:'transparent'}}>
                {dialogues.map((d, i) =>
                  <List.Item key={i} style={{background:'white', marginBottom:'0.3em'}}>
                    <h6 className="is-6" style={d.attributes.seen ? {} : unreadStyle}>{d.poster ? d.poster.attributes.name : ''}</h6>
                    <div style={replyMessageContentStyle}>
                      <p style={oneLineTruncationStyle}>{!d.attributes.seen ? <React.Fragment><b>[有新消息]</b>{` `}</React.Fragment> : ''}{d.message_body ? d.message_body.attributes.body : ''}</p>
                    </div>
                  </List.Item>)}
              </List>
            </Card>);
  }
}