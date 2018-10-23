import * as React from 'react';
import { Core } from '../../core';
import { Page } from '../components/common';
import { Login } from '../components/login';
import { ROUTE } from '../../config/route';
import { PasswordReset } from '../components/pwd-reset';

interface Props {
    core:Core;
}
interface State {
}

export class Login_m extends React.Component<Props, State> {
    public render () {
        console.log(window.location.pathname);
        return <Page>
            { this.renderContent() }
        </Page>;
    }

    public renderContent () {
        switch (window.location.pathname) {
            case ROUTE.login:
                return <Login core={this.props.core}></Login>;
            case ROUTE.register:
                return <div>register</div>;
            case ROUTE.reset_pwd:
                return <PasswordReset core={this.props.core}></PasswordReset>;
            default:
                return <div>get wrong pathname {window.location.pathname}</div>;
        }
    }
}