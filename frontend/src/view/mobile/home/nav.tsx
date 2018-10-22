import * as React from 'react';
import { Core } from '../../../core';
import { NavTop } from '../../components/common';

interface Props {
    core:Core;
    nav:(page:HomeNavE) => void;
}

interface State {

}

export enum HomeNavE {
    default,
    article,
    forum,
}

export class HomeNav_m extends React.Component<Props, State> {
    public render () {
        return (<NavTop items={[
            {to:HomeNavE.default, label: '首页', onClick:this.props.nav},
            {to:HomeNavE.article, label: '文库', onClick:this.props.nav},
            {to:HomeNavE.forum, label: '论坛', onClick:this.props.nav},
        ]}>
        </NavTop>);
    }
}