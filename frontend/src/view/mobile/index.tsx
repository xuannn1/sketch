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
import './common.scss';
import './index.scss';


interface Props {
    core:Core;
}

interface State {

}

export class Main_m extends React.Component<Props, State> {
    public render () {
        return (<div>
            <div className="main-frame">
                <Switch>
                    <Route exact path={ROUTE.home} component={Home_m} />
                    <Route path={ROUTE.collections} component={Collection_m} />
                    <Route path={ROUTE.users} component={User_m} />
                    <Route path={ROUTE.statuses} component={Status_m} />
                    <Route path={ROUTE.notifications} component={Notification_m} />
                </Switch>
            </div>

            <Navbar_m core={this.props.core} />
        </div>);
    }
}