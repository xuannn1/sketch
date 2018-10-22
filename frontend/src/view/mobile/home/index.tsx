import * as React from 'react';
import { Core } from '../../../core';
import { HomeDefault_m } from './default';
import { Article_m } from './article';
import { Forum_m } from './forum';
import { NavTop } from '../../components/common';

interface Props {
    core:Core;
}

interface State {
    nav:HomeNavE;
}

export enum HomeNavE {
    default,
    article,
    forum,
}

export class Home_m extends React.Component<Props, State> {
    public state = {
        nav: HomeNavE.default,
    };

    public handleNav = (nav:HomeNavE) => {
        this.setState({nav});
    }

    public render () {
        return (<div>
            <NavTop items={[
                {to:HomeNavE.default, label: '首页', onClick:this.handleNav},
                {to:HomeNavE.article, label: '文库', onClick:this.handleNav},
                {to:HomeNavE.forum, label: '论坛', onClick:this.handleNav}, 
            ]} />
            <div>
                { this.renderContent() }
            </div>
        </div>);
    }

    public renderContent () {
        switch (this.state.nav) {
            case HomeNavE.article:
                return <Article_m core={this.props.core} />
            case HomeNavE.forum:
                return <Forum_m core={this.props.core} />
            default:
                return <HomeDefault_m core={this.props.core} />
        }
    }
}