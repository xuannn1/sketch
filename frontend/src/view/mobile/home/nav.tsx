import * as React from 'react';
import { Core } from '../../../core';

interface Props {
    core:Core;
    nav:(page:HomeNavE) => void;
}

interface State {

}

export enum HomeNavE {
    default,
    article,
    forum,
}


export class HomeNav_m extends React.Component<Props, State> {
    public render () {
        return (<div className="nav">
            { this.renderButton(HomeNavE.default, '首页') }
            { this.renderButton(HomeNavE.article, '文库') }
            { this.renderButton(HomeNavE.forum, '论坛') }
        </div>);
    }

    public renderButton (nav:HomeNavE, text:string) {
        return <div><button onClick={() => this.props.nav(nav)}>{ text }</button></div>
    }
}