import * as React from 'react';
import { Core } from '../../core';
import { Navbar } from './navbar';

interface Props {
    core:Core;
}

interface State {

}

export class MainPC extends React.Component<Props, State> {
    public render () {
        return (<div>
            <Navbar 
                core={this.props.core}
            />
        </div>);
    }
}