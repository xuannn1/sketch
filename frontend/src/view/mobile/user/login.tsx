import * as React from 'react';
import { Login } from '../../components/user/login';
import { PasswordReset } from '../../components/user/pwd-reset';
import { Register } from '../../components/user/register';
import { NavBar } from '../../components/common/navbar';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common/page';

interface State {
}

export class LoginRoute extends React.Component<MobileRouteProps, State> {
  public location = '';

  public render () {
    const content = this.renderContent();

    return <Page top={<NavBar goBack={this.props.core.history.goBack}>Login</NavBar>} >
      { content }
    </Page>;
  }

  public renderContent () {
    // FIXME: location.state.from is '/user', we should probably use from.from
    const fromUrl = '/';
    switch (window.location.pathname) {
      case '/login':
        this.location = 'login';
        return <Login login={async (email, pwd) =>
            this.props.core.db.login(email, pwd, fromUrl) }></Login>;
      case '/register':
        this.location = 'register';
        return <Register register={async (name, email, pwd) =>
          this.props.core.db.register(name, pwd, email, fromUrl)}></Register>;
      case '/reset_password':
        this.location = 'reset password';
        return <PasswordReset resetPassword={(email) => this.props.core.db.resetPassword(email)}></PasswordReset>;
      default:
        return <div>wrong pathname {window.location.pathname}</div>;
    }
  }
}