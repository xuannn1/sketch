import * as React from 'react';
import { Card } from '../common';
import './book-chapters.scss';
import { ResData } from '../../../config/api';

interface Props {
    bookId:number;
    chapters:ResData.Post[];
}
interface State {
}

export class BookChapters extends React.Component<Props, State> {
    public render () {
        return <Card className="book-chapters">
            {this.props.chapters.map((chapter, i) =>
                <a href={`${window.location.origin}/book/${this.props.bookId}/chapter/${chapter.id}`} key={i}>{chapter.attributes.title}</a>
            )}
        </Card>;
    }
}