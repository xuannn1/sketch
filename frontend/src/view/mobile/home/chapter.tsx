import * as React from 'react';
import { RouteComponentProps } from 'react-router';
import { Core } from '../../../core/index';

interface Props extends RouteComponentProps<{
    bookId:string;
    chapterId:string;
}> {
    core:Core;
}

interface State {

}

export class Chapter extends React.Component<Props, State> {
    public render () {
        return <div>
            bookId: {this.props.match.params.bookId}, chapterId: {this.props.match.params.chapterId}
        </div>;
    }
}