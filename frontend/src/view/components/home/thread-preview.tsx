import * as React from 'react';
import { ResData } from '../../../config/api';
import { Link } from 'react-router-dom';
import { parseDate } from '../../../utils/date';

// todo: mini version
interface Props {
  data:ResData.Thread;
  mini?:boolean;
}
interface State {
}

export class ThreadPreview extends React.Component<Props, State> {
  public render () {
    const { attributes, id, author, tags, last_post } = this.props.data;
    const mini = this.props.mini || false; // true表示精简版， false表示非精简版

    return <div className="thread-item" key={id}>

    <div className="first-line">
      { !mini && tags && <span className="tags">
        {tags.map((tag, i) =>
          <Link className="tag"
            key={i}
            to={`/threads/?channels=[${attributes.channel_id}]&tags=[${tag.id}]`}>
          {tag.attributes.tag_name}</Link>)}
      </span>}
      <Link className="title"
        to={`/thread/${id}`}>{attributes.title}</Link>
    </div> 

    <div className="second-line">
      {attributes.brief}
    </div>

    {!mini && last_post && <div className="third-line">
      <span>{last_post.attributes.title}</span>
    </div>}

    <div className="meta">
      <Link className="author" to={`/user/${author.id}`}>{author.attributes.name}</Link>
      {attributes.created_at && attributes.edited_at &&
        <span className="date">
          {parseDate(attributes.created_at)}/{parseDate(attributes.edited_at)}
        </span>
      }

      {
        !mini && <span className="counters">
          <span><i className="fas fa-eye"></i>{attributes.view_count}</span> / 
          <span><i className="fas fa-comment-alt"></i>{attributes.reply_count}</span>  
        </span>
      }
    </div>
  </div>;
  }
}