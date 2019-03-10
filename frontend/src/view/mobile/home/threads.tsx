import * as React from 'react';
import { Page } from '../../components/common';
import { HomeNav } from './nav';
import { MobileRouteProps } from '../router';

interface State {
}

export class Threads extends React.Component<MobileRouteProps, State> {
    public render () {
        return <Page nav={<HomeNav />}>
        
        </Page>;
    }
}