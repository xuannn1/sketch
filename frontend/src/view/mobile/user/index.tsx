import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common';
import { Profile } from './profile';
import { Redirect } from 'react-router';


interface State {

}

export class User extends React.Component<MobileRouteProps, State> {
    public renderProfile() {
        return (<div>
            <button onClick={() => {
                this.props.core.user.logout();
            }}>log out</button>
            <Profile {...this.props}></Profile>
        </div>);
    }
    public render() {
        const isLogin = this.props.core.user.isLoggedIn()
        return (
            <Page>
                {isLogin ? this.renderProfile() : <Redirect to={{ pathname: './login', state: { from: this.props.location } }}></Redirect>}
            </Page>

        )

    }
    // public render () {
    //     return (<Page>
    //         <button onClick={() => {
    //             this.props.core.user.logout();
    //         }}>log out</button>
    //     </Page>);
    // }
}