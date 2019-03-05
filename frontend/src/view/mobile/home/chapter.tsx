import * as React from 'react';
import { MobileRouteProps } from '../router';

interface State {

}

export class Chapter extends React.Component<MobileRouteProps, State> {
    public render () {
        return <div>
            bookId: {this.props.match.params.bookId}, chapterId: {this.props.match.params.chapterId}
        </div>;
    }
}