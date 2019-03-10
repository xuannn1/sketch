import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common';
import { MessageNav } from './nav';

interface State {
}

export class MessageUnread extends React.Component<MobileRouteProps, State> {
    public render () {
        return <Page nav={<MessageNav />}>
            message unread 
        </Page>;
    }
}