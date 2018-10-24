import * as React from 'react';
import { Switch, Route } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { Collection_m } from './collection';
import { Home_m } from './home';
import { User_m } from './user';
import { Status_m } from './status';
import { Notification_m } from './notification';
import { Navbar_m } from './navbar';
import { Login_m } from './login';

interface Props {
    core:Core;
}

interface State {

}

export class Main_m extends React.Component<Props, State> {
    public render () {
        const { core } = this.props;
        const h = window.innerHeight;

        return (<div style={{
            position: 'absolute',
            height: '100%',
            width: `100%`,
        }}>
            <div className="container" style={{
                backgroundColor: '#f3f3f3',
                top: '0',
                height: `${(1 - (3.25 * 16 / h)) * 100}%`, // 3.25 rem is the bottom navbar height
                overflow: 'auto',
                width: '100%',
                position: 'relative',
            }}>
                <Switch>
                    <Route exact path={ROUTE.home}
                        render={(props) => <Home_m {...props} core={core} />}
                        core={this.props.core} />
                    <Route path={ROUTE.collections}
                        render={(props) => <Collection_m {...props} core={core} />} />
                    <Route path={ROUTE.users}
                        render={(props) => <User_m {...props} core={core} />} />
                    <Route path={ROUTE.statuses}
                        render={(props) => <Status_m {...props} core={core} />} />
                    <Route path={ROUTE.notifications}
                        render={(props) => <Notification_m {...props} core={core} />} />
                    <Route path={ROUTE.login}
                        render={(props) => <Login_m {...props} core={core} />} />
                </Switch>
            </div>

            <Navbar_m core={this.props.core} />
        </div>);
    }
}