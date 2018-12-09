import * as React from 'react';
import { Core } from '../../../core';
import { HomeMain } from './main';
import { Books } from './books';
import { Threads } from './threads';
import { TopMenu } from '../../components/common';
import { ROUTE } from '../../../config/route';
import { Switch } from 'react-router';

interface Props {
    core:Core;
    page:JSX.Element;
}

interface State {
}

export class Home extends React.Component<Props, State> {
    public render () {
        return (<div>
            <TopMenu items={[
                {to:ROUTE.home, label: '首页'},
                {to:ROUTE.books, label: '文库'},
                {to:ROUTE.threads, label: '论坛'}, 
            ]} />
            <div>
                { this.props.page }
            </div>
        </div>);
    }
}