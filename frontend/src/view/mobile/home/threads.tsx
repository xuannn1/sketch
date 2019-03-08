import * as React from 'react';
import { Page } from '../../components/common';
import { HomeTopNav } from './homenav';
import { MobileRouteProps } from '../router';

interface State {
}

export class Threads extends React.Component<MobileRouteProps, State> {
    public render () {
        return <Page nav={<HomeTopNav />}>
        
        </Page>;
    }
}