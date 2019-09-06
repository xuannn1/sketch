import * as React from 'react';
import { MobileRouteProps } from '../router';
// import { StatusNav } from './nav';
import { Page } from '../../components/common/page';
import { NavBar } from '../../components/common/navbar';
import { MessageMenu } from './message-menu';

interface State {

}

export class Message extends React.Component<MobileRouteProps, State> {
  public render () {
    return (<Page
        top={<NavBar goBack={this.props.core.history.goBack} onMenuClick={() => console.log('open setting')}>
          <MessageMenu/>
        </NavBar>}>
        Messages
      </Page>);
  }
}