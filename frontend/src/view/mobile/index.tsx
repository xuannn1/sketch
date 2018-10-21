import * as React from 'react';
import { Switch, Route } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { Collection_m } from './collection';
import { Home_m } from './home';
import { User_m } from './user';
import { Status_m } from './status';
import { Notification_m } from './notification';
// import { Navbar_m } from './navbar';
import './common.scss';
import './index.scss';


interface Props {
    core:Core;
}

interface State {

}

export class Main_m extends React.Component<Props, State> {
    public render () {
        const { core } = this.props;
        return (<div>
            <div className="main-frame">
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
                </Switch>
            </div>

            {/* <Navbar_m core={this.props.core} /> */}
        </div>);
    }
}