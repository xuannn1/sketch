import * as React from 'react';
import { MobileRouteProps } from '../router';

interface State {
}

export class MessageUnread extends React.Component<MobileRouteProps, State> {
    public render () {
        return <div>
            message unread 
        </div>;
    }
}