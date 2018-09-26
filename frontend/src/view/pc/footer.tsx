import * as React from 'react';
import { Core } from '../../core';

interface Props {
    core:Core;
}

interface State {

}

export class Footer extends React.Component<Props, State> {
    public render () {
        return (<div className="container">
            <div className="col-md-10 col-md-offset-1">
            </div>
        </div>);
    }
}