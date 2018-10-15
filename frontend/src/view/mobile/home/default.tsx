import * as React from 'react';
import { Core } from '../../../core';
import { Banner } from '../../components/banner';
import { HomeSuggestions } from '../../components/home/suggestion';
import { HomeForum } from '../../components/home/forum';
import { HomeNews } from '../../components/home/news';

interface Props {
    core:Core;
}

interface State {

}

export class HomeDefault_m extends React.Component<Props, State> {
    public render () {
        return (<div>
            <Banner core={this.props.core} />
            <button>贡献题头</button>
            <HomeSuggestions />
            <HomeForum />
            <HomeNews />
        </div>);
    }
}