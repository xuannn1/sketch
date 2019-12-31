import * as React from 'react';
import { MobileRouteProps } from '../router';
import { StatusNav } from './nav';
import { Page } from '../../components/common/page';

interface State {
}

export class StatusCollection extends React.Component<MobileRouteProps, State> {
  public render () {
    return <Page top={<StatusNav />}>
      status collection
    </Page>;
  }
}