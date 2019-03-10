import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page } from '../../components/common';

interface State {

}

export class Chapter extends React.Component<MobileRouteProps, State> {
    public render () {
        return <Page>
            bookId: {this.props.match.params.bookId}, chapterId: {this.props.match.params.chapterId}
        </Page>;
    }
}