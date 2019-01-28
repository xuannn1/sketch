import * as React from 'react';
import { Card } from '../common';

interface Props {
    title:string;
    brief:string;
    latestChapter:string;
    tags:string[];
    author:string;
    views:number;
    repies:number;
    total_char:number;
    isNew?:boolean;
}

interface State {
}

export class ThreadList extends React.Component<Props, State> {
    public render () {
        return <Card className="thread-list">
            <div className="title">{ this.props.title }</div>
            <div className="biref">{ this.props.brief }</div>
            <div className="latest-chapter">最新章节: { this.props.latestChapter }</div>
            <div className="tags">
                { this.props.tags.map((tag, i) => <div className="tag" key={i}>{tag}</div>)}
            </div>
            <div className="meta">
                <span className="author">{this.props.author}</span>
            </div>
        </Card>;
    }
}