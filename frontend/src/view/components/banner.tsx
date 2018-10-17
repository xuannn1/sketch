import * as React from 'react';
import { Core } from '../../core';
import { MyCard } from './common';

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
        return <MyCard>

        </MyCard>;
    }
}