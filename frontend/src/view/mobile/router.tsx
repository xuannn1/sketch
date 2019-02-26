import * as React from 'react';
import { Switch, Route, RouteComponentProps } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { Collection } from './collection';
import { Home } from './home';
import { User } from './user';
import { Status } from './status';
import { Notification } from './notification';
import { Navbar } from './navbar';
import { LoginRoute } from './login';
import { HomeMain } from './home/main';
import { Threads } from './home/threads';
import { Books } from './home/books';
import { Book } from './home/book';
import { Chapter } from './home/chapter';

interface Props {
    core:Core;
}

interface State {

}

export class MobileRoute extends React.Component<Props, State> {
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
                        render={(props) => <Home {...props} core={core} page={<HomeMain core={core} {...props} />} />} />
                    <Route path={ROUTE.books}
                        render={(props) => <Home {...props} core={core} page={<Books core={core} {...props} />} />} />
                    <Route path={ROUTE.threads}
                        render={(props) => <Home {...props} core={core} page={<Threads core={core} {...props} />} />} />
                    <Route path={ROUTE.chapter}
                        render={(props) => <Chapter {...props} core={core} />} />
                    <Route path={ROUTE.book}
                        render={(props) => <Book {...props} core={core} />} />
                    <Route path={ROUTE.collections}
                        render={(props) => <Collection {...props} core={core} />} />
                    <Route path={ROUTE.users}
                        render={(props) => <User {...props} core={core} />} />
                    <Route path={ROUTE.statuses}
                        render={(props) => <Status {...props} core={core} />} />
                    <Route path={ROUTE.notifications}
                        render={(props) => <Notification {...props} core={core} />} />
                    <Route path={ROUTE.login}
                        render={(props) => <LoginRoute {...props} core={core} />} />
                </Switch>
            </div>

            <Navbar core={this.props.core} />
        </div>);
    }
}