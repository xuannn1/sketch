import * as React from 'react';
import { Card } from '../common';
import { DataType } from '../../../config/data-types';
import './book-chapters.scss';

interface Props {
    data:DataType.Book.ChapterTitle[];
}
interface State {
}

export class BookChapters extends React.Component<Props, State> {
    public render () {
        return <Card className="book-chapters">
            {this.props.data.map((chapter, i) =>
                <a href={`${window.location.origin}/chapters/${chapter.id}`} key={i}>{chapter.title}</a>
            )}
        </Card>;
    }
}