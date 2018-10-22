import * as React from 'react';
import { Core } from '../../core';

import './styles/banner.scss';
import { Card } from './common';

interface Props {
    core:Core;
}

interface State {
}

export class Banner extends React.Component<Props, State> {
    public componentDidMount () {
    }

    public render () {
        return <Card>
            题头
        </Card>;
    }
}