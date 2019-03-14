import * as React from 'react';
import { ResData } from '../../../config/api';
import { Link } from 'react-router-dom';

// todo: mini version
interface Props {
    data:ResData.Thread;
    mini?:boolean;
}
interface State {
}

export class BookPreview extends React.Component<Props, State> {
    public render () {
        const { attributes, id, author, tags, last_component } = this.props.data;
        return <div className="book-item" key={id}>
            <Link className="title" to={`/book/${id}`}>{ attributes.title }</Link>
            <div className="biref">{ attributes.brief }</div>
            { last_component &&
                <span className="latest-chapter">最新章节: { last_component.attributes.title }</span>
            }
            { tags &&
                <div className="tags">
                    { tags.map((tag, i) => 
                        <Link className="tag" key={i} to={`/books/?tags=[${tag.id}]`}>{tag.attributes.tag_name}</Link>
                    )}
                </div> 
            }
            <div className="meta">
                <Link className="author" to={`/user/${author.id}`}>{author.attributes.name}</Link>
                <div className="counters">
                    <span><i className="fas fa-pencil-alt"></i>{attributes.total_char}</span> /
                    <span><i className="fas fa-eye"></i>{attributes.view_count}</span> / 
                    <span><i className="fas fa-comment-alt"></i>{attributes.reply_count}</span> /
                </div>
            </div>
        </div>;
    }
}