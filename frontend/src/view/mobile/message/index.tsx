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

interface State {

}
const badgeStyle:React.CSSProperties = {float:'right'};

export class Message extends React.Component<MobileRouteProps, State> {
  public render () {
    return (<Page
        top={<NavBar goBack={this.props.core.route.back} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>
        <div className="blank-block right-align">
          <a>全部标记已读</a>
        </div>

        <Card>
          <List>
            <List.Item onClick={() => console.log('click item ')} arrow={true}>
              <span className="icon-with-right-text">
                <i className="far fa-thumbs-up icon" />
                <span>点赞提醒</span>
              </span>
              <Badge num={1000} max={100} style={badgeStyle}/>
            </List.Item>
            <List.Item onClick={() => console.log('click item ')} arrow={true}>
              <span className="icon-with-right-text">
                <i className="fas fa-gift icon"></i>
                <span>打赏提醒</span>
              </span>
              <Badge num={1} max={100} style={badgeStyle}/>
            </List.Item>
          </List>
        </Card>

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