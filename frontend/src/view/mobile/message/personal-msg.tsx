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

  private renderMessages () {
    const { messages } = this.state.data;
    // TODO: mark msg as read
    return (<Card style={replyNotificationCardStyle}>
              <List style={{background:'transparent'}}>
                {messages.map((m, i) =>
                  <List.Item key={i} style={{background:'white', marginBottom:'0.3em'}}>
                    <h6 className="is-6" style={m.attributes.seen ? {} : unreadStyle}>{m.poster ? m.poster.attributes.name : ''}</h6>
                    <div style={replyMessageContentStyle}>
                      <p style={oneLineTruncationStyle}>{!m.attributes.seen ? <React.Fragment><b>[有新消息]</b>{` `}</React.Fragment> : ''}{m.message_body ? m.message_body.attributes.body : ''}</p>
                    </div>
                  </List.Item>)}
              </List>
            </Card>);
  }
}