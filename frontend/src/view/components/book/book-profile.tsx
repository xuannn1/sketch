import * as React from 'react';
import { Card } from '../common';
import './book-profile.scss';
import { parseDate } from '../../../utils/date';
import { ResData } from '../../../config/api';

interface Props {
    thread:ResData.Thread;
}
interface State {
}

export class BookProfile extends React.Component<Props, State> {
    public render () {
        const { attributes, author, tags } = this.props.thread;

        return <Card className="book-profile" style={{
            padding: '3em 3em',
        }}>
            <div className="title is-2">{attributes.title}</div>
            <div className="brief">{attributes.brief}</div>

            <div className="brief">
                <a className="username" href={`${window.location.origin}/users/${author.id}`}>{author.attributes.name}</a>
                <span className="publish-date">发表于{parseDate(attributes.created_at || '')} 修改于{parseDate(attributes.edited_at || '')}</span>
            </div>

            { tags &&
                <div className="intro">
                    <div className="tags">
                        {tags.map((tag, i) => 
                            <a key={i} href={`${window.location.origin}/book-tag/${tag.id}`}>{tag.attributes.tag_name}</a>)}
                    </div>
                </div> 
            }


            <div className="counters">
                <span><i className="fas fa-pencil-alt"></i>{attributes.total_char}</span> /
                <span><i className="fas fa-eye"></i>{attributes.view_count}</span> / 
                <span><i className="fas fa-comment-alt"></i>{attributes.reply_count}</span> /
                <span><i className="fas fa-download"></i>{attributes.download_count}</span>
            </div>

            <div className="change-mode">
                {this.renderModeText()}
            </div>
        </Card>;
    }

    public renderModeText () {
        const mode = window.location.pathname.split('/')[1];
        if (!mode) { return <></>; }
        if (mode === 'threads') {
            return <a href={`${window.location.origin}/book/${this.props.thread.id}`}><i className="fas fa-book"></i>文库阅读模式</a>;
        } else {
            return <a href={`${window.location.origin}/threads/${this.props.thread.id}`}><i className="fas fa-comment"></i>论坛讨论模式</a>;
        }
    }
}