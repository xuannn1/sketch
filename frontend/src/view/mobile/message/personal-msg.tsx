import * as React from 'react';
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

interface State {

}

export class PersonalMessage extends React.Component<MobileRouteProps, State> {
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
        <Card style={replyNotificationCardStyle}>
        <List style={{background:'transparent'}}>
          {mockReplyNotifications.map((n, i) =>
          <List.Item key={i} style={{background:'white', marginBottom:'0.3em'}}>
            <h6 className="is-6" style={n.read ? {} : unreadStyle}>{n.author}</h6>
            <div style={replyMessageContentStyle}>
              <p style={oneLineTruncationStyle}>{!n.read ? <React.Fragment><b>[有新消息]</b>{` `}</React.Fragment> : ''}{n.message}</p>
            </div>
          </List.Item>)}
        </List>
        </Card>
      </Page>);
  }
}