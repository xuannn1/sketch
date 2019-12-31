import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common/page';
import { List } from '../../components/common/list';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';
import { Badge } from '../../components/common/badge';
import ClampLines from 'react-clamp-lines';
import { pageStyle, largeListItemStyle, badgeStyle, topCardStle, contentCardStyle, replyNotificationCardStyle, replyMessageContentStyle, unreadStyle } from './styles';
import { mockReplyNotifications } from './mock-data';

interface State {

}

export class Message extends React.Component<MobileRouteProps, State> {
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
              <i className="far fa-thumbs-up icon"></i>
              点赞提醒
              <Badge num={1000} max={100} style={badgeStyle}/>
            </List.Item>
            <List.Item onClick={() => console.log('click item ')} arrow={true} style={largeListItemStyle}>
              <i className="fas fa-gift icon"></i>
              打赏提醒
              <Badge num={1} max={100} style={badgeStyle}/>
            </List.Item>
          </List>
        </Card>
        {/* reply notifications */}
        <Card style={replyNotificationCardStyle}>
        <List style={{background:'transparent'}}>
          {mockReplyNotifications.map((n, i) =>
          <List.Item key={i} style={{background:'white', marginBottom:'0.3em'}}>
            <h6 className="is-6" style={n.read ? {} : unreadStyle}>{n.author}回复了你的主题{n.title}</h6>
            <div style={replyMessageContentStyle}>
              <ClampLines
                text={n.message}
                id={'text' + i}
                lines={2}
                ellipsis="..."
                buttons={false}
                innerElement="p"/>
            </div>
          </List.Item>)}
        </List>
        </Card>
      </Page>);
  }
}