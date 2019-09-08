import * as React from 'react';
import { MobileRouteProps } from '../router';
// import { StatusNav } from './nav';
import { Page } from '../../components/common/page';
import { List } from '../../components/common/list';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';
import { Badge } from '../../components/common/badge';

interface State {

}

const pageStyle:React.CSSProperties = {
  display: 'flex',
  flexDirection: 'column',
};
const largeListItemStyle:React.CSSProperties = {
  padding: '1em 1em',
};
const badgeStyle:React.CSSProperties = {float:'right', marginTop:'2px'};
const topCardStle:React.CSSProperties = {
  border: 'none',
  backgroundColor: '#f4f5f9',
  textAlign: 'right',
  boxShadow: 'none',
};
const contentCardStyle:React.CSSProperties = {
  margin: '0px',
  padding:'0px',
  border: 'none',
  backgroundColor: 'transparent',
  textAlign: 'left',
  boxShadow: 'none',
};
const replyNotificationCardStyle:React.CSSProperties = {
  border: 'none',
  paddingLeft: '0px',
  paddingRight: '0px',
  backgroundColor: '#f4f5f9',
  boxShadow: 'none',
  marginTop: '0px',
  flexGrow: 1,
};
const replyMessageContentStyle:React.CSSProperties = {
  height: '4.5em',
  overflow: 'hidden',
};

// mock data
const mockReplyNotifications = [{
  author: 'Alex',
  title: 'Hello World',
  message: 'Expanding the #down child to fill the remaining space of #container can be accomplished in various ways depending on the browser support you wish to achieve and whether or not #up has a defined height.',
},{
  author: 'Alex',
  title: 'Hello World',
  message: 'Expanding the #down child to fill the remaining space of #container can be accomplished in various ways depending on the browser support you wish to achieve and whether or not #up has a defined height.',
},{
  author: 'Alex',
  title: 'Hello World',
  message: 'Expanding the #down child to fill the remaining space of #container can be accomplished in various ways depending on the browser support you wish to achieve and whether or not #up has a defined height.',
},{
  author: 'Alex',
  title: 'Hello World',
  message: 'Expanding the #down child to fill the remaining space of #container can be accomplished in various ways depending on the browser support you wish to achieve and whether or not #up has a defined height.',
},{
  author: 'Alex',
  title: 'Hello World',
  message: 'Expanding the #down child to fill the remaining space of #container can be accomplished in various ways depending on the browser support you wish to achieve and whether or not #up has a defined height.',
},{
  author: 'Alex',
  title: 'Hello World',
  message: 'Expanding the #down child to fill the remaining space of #container can be accomplished in various ways depending on the browser support you wish to achieve and whether or not #up has a defined height.',
}];

// TODO: add multiline line truncation: http://hackingui.com/front-end/a-pure-css-solution-for-multiline-text-truncation/

export class Message extends React.Component<MobileRouteProps, State> {
  public render () {
    return (<Page style={pageStyle}
        top={<NavBar goBack={this.props.core.history.goBack} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>
        <Card style={topCardStle}>
          <a className="is-text">mark all as read</a>
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
            <h6 className="title is-6">{n.author}回复了你的主题{n.title}</h6>
            <div style={replyMessageContentStyle}>{n.message}</div>
          </List.Item>)}
        </List>
        </Card>
      </Page>);
  }
}