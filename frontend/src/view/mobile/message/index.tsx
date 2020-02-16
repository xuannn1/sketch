import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common/page';
import { List } from '../../components/common/list';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';
import { Badge } from '../../components/common/badge';
import ClampLines from 'react-clamp-lines';
import './style.scss';
import { mockReplyNotifications } from './mock-data';
import { MarkAllAsRead } from './mark-all-as-read';
import { Mark } from '../../components/common/mark';
import { Menu, MenuItem } from '../../components/common/menu';

interface State {

}

export class Message extends React.Component<MobileRouteProps, State> {
  public render () {
    return (<Page
        top={<NavBar goBack={this.props.core.route.back} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>

        <MarkAllAsRead />

        <Menu>
          <MenuItem icon="far fa-thumbs-up icon" title="点赞提醒" badgeNum={1000}/>
          <MenuItem icon="fas fa-gift icon" title="打赏提醒" badgeNum={1}/>
        </Menu>

        <List className="message-list">
          {mockReplyNotifications.map((n, i) =>
          <List.Item key={i}>
            <div className="item-container">
              <div className="item-first-line">
                <div className={n.read ? '' : 'unread'}>{n.author}回复了你主题{n.title}</div>
              </div>
              <div className="item-brief">
                <ClampLines
                  text={n.message}
                  id={'text' + i}
                  lines={2}
                  ellipsis="..."
                  buttons={false}
                  innerElement="p"/>
                </div>
            </div>
          </List.Item>)}
        </List>
      </Page>);
  }
}