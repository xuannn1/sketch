import * as React from 'react';
import { Core } from '../../../core';

interface Props {
    core:Core;
}

interface State {

}

export class User extends React.Component<Props, State> {
    public render () {
        return (<div>
            <button onClick={() => {
                this.props.core.user.logout();
            }}>log out</button>
        </div>);
    }
}