import * as React from 'react';
import { Switch, Route, RouteComponentProps } from 'react-router-dom';
import { Core } from '../../core';
import { Collection } from './collection';
import { User } from './user';
import { Status } from './status';
import { Notification } from './notification';
import { Navbar } from './navbar';
import { LoginRoute } from './login';
import { HomeMain } from './home/main';
import { HomeThread } from './home/homethread';
import { HomeBook } from './home/homebook';
import { Book } from './home/book';
import { Chapter } from './home/chapter';
import { Thread } from './home/thread';
import { Threads } from './home/threads';
import { Books } from './home/books';

interface Props {
    core:Core;
}

interface State {

}


export interface MobileRouteProps extends RouteComponentProps<any> {
    core:Core;
    path:string;
}
export type RouteComponentType = {
    path:string;
    component:React.ComponentClass<MobileRouteProps,any>;
    exact?:boolean;
}
export const MobileRoute:RouteComponentType[] = [
    { path: '/', component: HomeMain, exact: true },
    { path: '/homebook', component: HomeBook },
    { path: '/homethread', component: HomeThread },
    { path: '/threads', component: Threads },
    { path: '/books', component: Books },
    { path: '/book', component: Book },
    { path: '/thread', component: Thread },
    { path: '/chapter', component: Chapter },
    { path: '/login', component: LoginRoute },
    { path: '/collections', component: Collection },
    { path: '/user', component: User },
    { path: '/status', component: Status },
    { path: '/notifications', component: Notification },
];

export class MobileRouter extends React.Component<Props, State> {
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
                    {MobileRoute.map((route, i) =>
                        <Route exact={route.exact || false}
                            path={route.path}
                            key={i}
                            render={(props) => React.createElement(
                                route.component,
                                {
                                    core,
                                    path: route.path,
                                    ...props,
                                },
                            )}
                        />
                    )}
                </Switch>
            </div>

            <Navbar core={this.props.core} />
        </div>);
    }
}