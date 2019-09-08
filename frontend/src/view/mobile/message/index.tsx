import * as React from 'react';
import { MobileRouteProps } from '../router';
// import { StatusNav } from './nav';
import { Page } from '../../components/common/page';
import { List } from '../../components/common/list';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';
import { Card } from '../../components/common/card';

interface State {

}

const largeListItemStyle = {
  padding: '1em 1em',
};

export class Message extends React.Component<MobileRouteProps, State> {
  public render () {
    return (<Page
        top={<NavBar goBack={this.props.core.history.goBack} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>
        <Card style={{
          border: 'none',
          backgroundColor: '#f4f5f9',
          textAlign: 'right',
          boxShadow: 'none',
        }}>
          <a className="is-text">mark all as read</a>
        </Card>
        <Card style={{
          margin: '0px',
          padding:'0px',
          border: 'none',
          backgroundColor: 'transparent',
          textAlign: 'left',
          boxShadow: 'none',
        }}>
          <List>
            <List.Item onClick={() => alert('click item ')} arrow={true} style={largeListItemStyle}>
              <i className="far fa-thumbs-up icon"></i> 点赞提醒
            </List.Item>
            <List.Item onClick={() => alert('click item ')} arrow={true} style={largeListItemStyle}>
              <i className="fas fa-gift icon"></i> 打赏提醒
            </List.Item>
          </List>
        </Card>
      </Page>);
  }
}