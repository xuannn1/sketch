import * as React from 'react';
import { Core } from '../../../core';
import { HomeNav_m, HomeNav } from './nav';
import { HomeDefault_m } from './default';
import { Article_m } from './article';
import { Forum_m } from './forum';

interface Props {
    core:Core;
}

interface State {
    nav:HomeNav;
}

export class Home_m extends React.Component<Props, State> {
    public render () {
        return (<div>
            <HomeNav_m core={this.props.core} nav={(page:HomeNav) => this.setState({ nav:page })} />
            <div className="content">
                { this.renderContent() }
            </div>
        </div>);
    }

    public renderContent () {
        switch (this.state.nav) {
            case HomeNav.article:
                return <Article_m core={this.props.core} />
            case HomeNav.forum:
                return <Forum_m core={this.props.core} />
            default:
                return <HomeDefault_m core={this.props.core} />
        }
    }
}