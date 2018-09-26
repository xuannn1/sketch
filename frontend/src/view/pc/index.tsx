import * as React from 'react';
import { BrowserRouter } from 'react-router-dom';
import { Core } from '../../core';
import { Navbar } from './header';
import { AlertMsg } from '../../components/alert-msg';
import { Search } from '../../components/search';
import { Content } from './content';
import { Footer } from './footer';

interface Props {
    core:Core;
}

export class Main_pc extends React.Component<Props, {}> {
    public render () {
        return (<BrowserRouter>
            <Navbar core={this.props.core} />
            <AlertMsg core={this.props.core} />
            <Search core={this.props.core} />
            <Content core={this.props.core} />
            <Footer core={this.props.core} />
        </BrowserRouter>);
    }
}