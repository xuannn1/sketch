import * as React from 'react';
import { Core } from '../../../core';
import { HomeNav_m, HomeNavE } from './nav';
import { HomeDefault_m } from './default';
import { Article_m } from './article';
import { Forum_m } from './forum';
import './home.scss';

interface Props {
    core:Core;
}

interface State {
    nav:HomeNavE;
}

export class Home_m extends React.Component<Props, State> {
    public state = {
        nav: HomeNavE.default,
    };

    public render () {
        return (<div className="home">
            <HomeNav_m core={this.props.core} nav={(page:HomeNavE) => this.setState({ nav:page })} />
            <div className="content">
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