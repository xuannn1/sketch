import * as React from 'react';
import { Core } from '../../core';
import './styles/banner.scss';

interface Props {
    core:Core;
}

interface State {
}

export class Banner extends React.Component<Props, State> {
    public componentDidMount () {
    }

    public render () {
        return <div className="banner">
            <div>
            </div>
        </div>;
    }
}