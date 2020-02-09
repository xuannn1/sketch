import * as React from 'react';
import { Switch, Route, RouteComponentProps } from 'react-router-dom';
import { Core } from '../../core';
import { CollectionBook } from './collection/book';
import { User } from './user';
import { Message } from './message';
import { PersonalMessage } from './message/personal-msg';
import { Dialogue } from './message/dialogue';
import { PublicNotice } from './message/public-notice';
import { Status } from './status';
import { LoginRoute } from './user/login';
import { HomeMain } from './home/main';
import { Chapter } from './home/chapter';
import { StatusCollection } from './status/collection';
import { CollectionList } from './collection/list';
import { CreateQuote } from './home/createquote';

import { Tidings } from './tidings';
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
  component:React.ComponentClass<MobileRouteProps, any>;
  exact?:boolean;
};
export const MobileRoute:RouteComponentType[] = [
  // home
  { path: '/', component: HomeMain, exact: true },
  // { path: '/homebook', component: HomeBook },
  // { path: '/homethread', component: HomeThread },
  // { path: '/threads', component: Threads },
  // { path: '/books', component: Books },
  { path: '/book/:bid/chapter/:cid', component: Chapter },
  // { path: '/book/:id', component: Book },
  // { path: '/thread/:id', component: Thread },

  // user
  { path: '/user', component: User },
  { path: '/login', component: LoginRoute },
  { path: '/register', component: LoginRoute },

  // collection
  { path: '/collection/book', component: CollectionBook },
  // { path: '/collection/thread', component: CollectionThread },
  { path: '/collection/list', component: CollectionList },

  // status
  { path: '/status/collection', component: StatusCollection },
  { path: '/status/all', component: Status },

  // message
  { path: '/messages/pm/:uid', component: Dialogue },
  { path: '/messages/pm', component: PersonalMessage },
  { path: '/messages/publicnotice', component: PublicNotice },
  { path: '/messages', component: Message },

  { path: '/tidings', component: Tidings },
  // others
  { path: '/createquote', component: CreateQuote },
];

export class MobileRouter extends React.Component<Props, State> {
  public render () {
    const { core } = this.props;

    return (<div>
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
          />,
        )}
      </Switch>
    </div>);
  }
}