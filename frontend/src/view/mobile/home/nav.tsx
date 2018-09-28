import * as React from 'react';
import { Core } from '../../../core';

interface Props {
    core:Core;
    nav:(page:HomeNav) => void;
}

interface State {

}

export enum HomeNav {
    default,
    article,
    forum,
}


export class HomeNav_m extends React.Component<Props, State> {
    public render () {
        return (<div>
            <span><a onClick={() => this.props.nav(HomeNav.default)}>首页</a></span>
        </div>);
    }
}