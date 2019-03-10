import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common';
import { Topnav } from '../../components/topnav';

interface State {

}

export class Chapter extends React.Component<MobileRouteProps, State> {
    public render () {
        return <Page nav={<Topnav core={this.props.core} center={''} />}>
            bookId: {this.props.match.params.bid}, chapterId: {this.props.match.params.cid}
        </Page>;
    }
}