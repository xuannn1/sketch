import * as React from 'react';
import { Core } from '../../core';
import { Page } from '../components/common';
import { Login } from '../components/login/login';
import { ROUTE } from '../../config/route';
import { PasswordReset } from '../components/login/pwd-reset';
import { Register } from '../components/login/register';
import { Topnav } from '../components/topnav';

interface Props {
    core:Core;
}
interface State {
}

export class Login_m extends React.Component<Props, State> {
    public location = '';

    public render () {
        const content = this.renderContent();

        return <Page nav={<Topnav core={this.props.core} text={this.location} />}>
            { content }
        </Page>;
    }

    public renderContent () {
        switch (window.location.pathname) {
            case ROUTE.login:
                this.location = 'login';
                return <Login core={this.props.core}></Login>;
            case ROUTE.register:
                this.location = 'register';
                return <Register core={this.props.core}></Register>;
            case ROUTE.reset_pwd:
                this.location = 'reset password';
                return <PasswordReset core={this.props.core}></PasswordReset>;
            default:
                return <div>wrong pathname {window.location.pathname}</div>;
        }
    }
}