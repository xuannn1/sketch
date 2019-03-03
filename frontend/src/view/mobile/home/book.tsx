import * as React from 'react';
import { Core } from '../../../core/index';
import { Page, Anchor } from '../../components/common';
import { BookProfile } from '../../components/book/book-profile';
import { BookChapters } from '../../components/book/book-chapters';
import { APIGet, ResData } from '../../../config/api';
import { RouteComponentProps } from 'react-router';
import { Topnav } from '../../components/topnav';

interface Props extends RouteComponentProps<{
    id:string;
}> {
    core:Core;
}

interface State {
    data:APIGet['/book/:id']['res']['data'];
}

export class Book extends React.Component<Props, State> {
    public state:State = {
        data: {
            thread: ResData.allocThread(),
            chapters: [],
            volumns: [],
            most_upvoted: ResData.allocPost(),
            top_review: null,
            paginate: ResData.allocThreadPaginate(),
        }
    };

    public async componentDidMount () {
        const res = await this.props.core.db.get('/book/:id', {
            id: +this.props.match.params.id,
        });
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
    }

    public render () {
        return (
            <Page nav={
                <Topnav core={this.props.core} 
                center={
                    <div className="buttons">
                        <Anchor className="button" isDisabled={true} href={''}>目录模式</Anchor>
                        <Anchor className="button" href={'' /* fixme: */}>论坛模式</Anchor>
                    </div>
                }
                right={<a className="button">+</a> /* fixme: */}
                />
            }>
                <BookProfile thread={this.state.data.thread} />
                <BookChapters bookId={+this.props.match.params.id} chapters={this.state.data.chapters} />
            </Page>
        );
    }
}