import * as React from 'react';
import { Handler } from '../handlers';
import { isMobile } from '../utils/mobile';
import { MainMobile } from './mobile';
import { MainPC } from './pc';

interface Props {
    h:Handler;
}

interface State {

}

export class Main extends React.Component<Props, State> {
    public render () {
        if (isMobile()) {
            return <MainMobile h={this.props.h} />
        } else {
            return <MainPC h={this.props.h} />
        }
    }
}