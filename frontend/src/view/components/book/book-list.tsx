import * as React from 'react';
import { Card } from '../common';
import { ResData } from '../../../config/api';
import { ROUTE } from '../../../config/route';
import { Link } from 'react-router-dom';

interface Props {
    thread:ResData.Thread;
    latestChapter:string;
    chapterId:string;
}

interface State {
}

export class BookList extends React.Component<Props, State> {
    public render () {
        const { attributes, id, author, channel, tags, recommendations } = this.props.thread;
        return <Card className="thread-list">
            <a className="title" href={`${window.location.origin}${ROUTE.books}/${id}`}>{ attributes.title }</a>
            <div className="biref">{ attributes.brief }</div>
            <a className="latest-chapter" href={'' /* fixme: */}>最新章节: { this.props.latestChapter }</a>
            { tags &&
                <div className="tags">
                    { tags.map((tag, i) => 
                        <a className="tag" key={i} href={`${window.location.origin}${ROUTE.books}/?tag=${tag.id}`}>{tag.attributes.tag_name}</a>
                    )}
                </div> 
            }
            <div className="meta">
                <a className="author" href={`${window.location.origin}${ROUTE.users}/${author.id}`}>{author.attributes.name}</a>
                <div className="counters">
                    <span><i className="fas fa-pencil-alt"></i>{attributes.total_char}</span> /
                    <span><i className="fas fa-eye"></i>{attributes.view_count}</span> / 
                    <span><i className="fas fa-comment-alt"></i>{attributes.reply_count}</span> /
                </div>
            </div>
        </Card>;
    }
}