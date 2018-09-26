import * as React from 'react';
import { Core } from '../core';
import { isMobile } from '../utils/mobile';
import { Main_m } from './mobile';
import { Main_pc } from './pc';

interface Props {
    core:Core;
}

interface State {

}

export class Main extends React.Component<Props, State> {
    public render () {
        if (isMobile()) {
            return <Main_m core={this.props.core} />
        } else {
            return <Main_pc core={this.props.core} />
        }
    }
}