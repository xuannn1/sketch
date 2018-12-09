import * as React from 'react';
import { Card } from '../common';
import { DataType } from '../../../config/data-types';
import './book-profile.scss';
import { parseDate } from '../../../utils/date';

interface Props {
    data:DataType.Book.Profile;
}
interface State {
}

export class BookProfile extends React.Component<Props, State> {
    public render () {
        const { data } = this.props;
        console.log(data);

        return <Card className="book-profile" style={{
            padding: '3em 3em',
        }}>
            <div className="title is-2">{data.title}</div>
            <div className="brief">{data.brief}</div>

            <div className="brief">
                <a className="username" href={`${window.location.origin}/users/${data.user.id}`}>{data.user.name}</a>
                <span className="publish-date">发表于{parseDate(data.publishDate)} 修改于{parseDate(data.updateDate)}</span>
            </div>

            <div className="intro">
                <div className="tags">
                    {data.tags.map((tag, i) => 
                        <a key={i} href={`${window.location.protocol}//${window.location.hostname}/book-tag/${tag.id}`}>{tag.name}</a>)}
                </div>
            </div>

            <div className="counters">
                <span><i className="fas fa-pencil-alt"></i>{data.wordCounter}</span> /
                <span><i className="fas fa-eye"></i>{data.viewCounter}</span> / 
                <span><i className="fas fa-comment-alt"></i>{data.commentCounter}</span> /
                <span><i className="fas fa-download"></i>{data.downloadCounter}</span>
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
            return <a href={`${window.location.origin}/books/${this.props.data.id}`}><i className="fas fa-book"></i>文库阅读模式</a>;
        } else {
            return <a href={`${window.location.origin}/threads/${this.props.data.threadId}`}><i className="fas fa-comment"></i>论坛讨论模式</a>;
        }
    }
}