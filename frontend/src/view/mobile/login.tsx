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

export class LoginRoute extends React.Component<Props, State> {
    public location = '';

    public render () {
        const content = this.renderContent();

        return <Page nav={<Topnav core={this.props.core} title={this.location} />}>
            { content }
        </Page>;
    }

    public renderContent () {
        switch (window.location.pathname) {
            case ROUTE.login:
                this.location = 'login';
                return <Login login={async (email, pwd) => await this.props.core.user.login(email, pwd)}></Login>;
            case ROUTE.register:
                this.location = 'register';
                return <Register register={async (name, email, pwd) => await this.props.core.user.register({
                    email,
                    name,
                    password: pwd,
                })}></Register>;
            case ROUTE.reset_pwd:
                this.location = 'reset password';
                return <PasswordReset resetPassword={(email) => this.props.core.db.resetPassword(email) as any}></PasswordReset>;
            default:
                return <div>wrong pathname {window.location.pathname}</div>;
        }
    }
}