import * as React from 'react';
import { Switch, Route } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { Home } from './home';

interface Props {
    core:Core;
}

interface State {

}

export class Content extends React.Component<Props, State> {
    public render () {
        return (<Switch>
            <Route exact path={ROUTE.home} app={Home} />
        </Switch>);
    }
}
