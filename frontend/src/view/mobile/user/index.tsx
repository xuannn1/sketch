import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common';


interface State {

}

export class User extends React.Component<MobileRouteProps, State> {
    public render () {
        return (<Page>
            <button onClick={() => {
                this.props.core.user.logout();
            }}>log out</button>
        </Page>);
    }
}