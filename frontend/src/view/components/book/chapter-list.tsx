import * as React from 'react';
import { Link } from 'react-router-dom';
import { Card } from '../common';
import { ResData } from '../../../config/api';
import './chapter-list.scss';

interface Props {
    bookId:number;
    chapters:ResData.Post[];
}
interface State {
}

export class ChapterList extends React.Component<Props, State> {
    public render () {
        return <Card className="book-chapters">
            {this.props.chapters.map((chapter, i) =>
                <Link to={`/book/${this.props.bookId}/chapter/${chapter.id}`} key={i}>{chapter.attributes.title}</Link>
            )}
        </Card>;
    }
}