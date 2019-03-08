import * as React from 'react';
import { ResData } from '../../../config/api';
import { Card, Pagination } from '../common';
import { Link } from 'react-router-dom';
import { parseDate } from '../../../utils/date';

interface Props {
    threads:ResData.Thread[];
    paginate:ResData.ThreadPaginate;
    channelId:number;
}
interface State {
}

export class ThreadList extends React.Component<Props, State> {
    public render () {
        return <Card className='thread-list'>
            {this.props.threads.map((thread) => this.renderThreadItem(thread))}
            <Pagination
                currentPage={this.props.paginate.current_page}
                lastPage={this.props.paginate.total_pages}
            />
        </Card>;
    }

    public renderThreadItem (thread:ResData.Thread) {
        const { attributes, id, author, tags, last_post } = thread;
        return <div className="thread-item" key={id}>

            <div className="first-line">
                {tags && <span className="tags">
                    {tags.map((tag, i) =>
                        <Link className="tag"
                            key={i}
                            to={`/threads/?channels=[${this.props.channelId}]&tags=[${tag.id}]`}>
                        {tag.attributes.tag_name}</Link>)}
                </span>}
                <Link className="title"
                    to={`/thread/${id}`}>{attributes.title}</Link>
            </div> 

            <div className="second-line">
                {attributes.brief}
            </div>

            {last_post && <div className="third-line">
                <Link to={`/thread/${id}/?page=${this.props.paginate.total_pages}#post${last_post.id}`}>{last_post.attributes.title}</Link>
            </div>}

            <div className="meta">
                <Link className="author" to={`/user/${author.id}`}>{author.attributes.name}</Link>
                {attributes.created_at && attributes.edited_at &&
                    <span className="date">
                        {parseDate(attributes.created_at)}/{parseDate(attributes.edited_at)}
                    </span>
                }
                <span className="counters">
                    <span><i className="fas fa-eye"></i>{attributes.view_count}</span> / 
                    <span><i className="fas fa-comment-alt"></i>{attributes.reply_count}</span>  
                </span>
            </div>
            <hr />
        </div>;
    }
}