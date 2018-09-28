import * as React from 'react';
import { Core } from '../core';
import { isMobile } from '../utils/mobile';
import { Main_m } from './mobile';
import { Main_pc } from './pc';
import { BrowserRouter } from 'react-router-dom';

interface Props {
    core:Core;
}

interface State {

}

export class App extends React.Component<Props, State> {
    public renderApp () {
        return <Main_m core={this.props.core} />;
        // if (isMobile()) {
        //     return <Main_m core={this.props.core} />
        // } else {
        //     return <Main_pc core={this.props.core} />
        // }
    }

    public render () {
        return (
            <BrowserRouter>
                { this.renderApp() }
            </BrowserRouter>
        )
    }
}