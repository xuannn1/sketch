import * as React from 'react';
import { HomeTopNav } from './homenav';
import { Page } from '../../components/common';
import { MobileRouteProps } from '../router';

interface State {
}

export class Books extends React.Component<MobileRouteProps, State> {
    public render () {
        return <Page nav={<HomeTopNav />}>
            
        </Page>;
    }
}