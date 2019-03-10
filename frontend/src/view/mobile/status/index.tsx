import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common';
import { StatusNav } from './nav';
interface State {

}

export class Status extends React.Component<MobileRouteProps, State> {
    public render () {
        return (<Page nav={<StatusNav />}>
            status
        </Page>);
    }
}