import * as React from 'react';
import { MobileRouteProps } from '../router';


interface State {

}

export class User extends React.Component<MobileRouteProps, State> {
    public render () {
        return (<div>
            <button onClick={() => {
                this.props.core.user.logout();
            }}>log out</button>
        </div>);
    }
}