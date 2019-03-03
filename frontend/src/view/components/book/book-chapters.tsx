import * as React from 'react';
import { Card } from '../common';
import './book-chapters.scss';
import { ResData } from '../../../config/api';
import { Link } from 'react-router-dom';

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
                <Link to={`/book/${this.props.bookId}/chapter/${chapter.id}`} key={i}>{chapter.attributes.title}</Link>
            )}
        </Card>;
    }
}