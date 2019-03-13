import * as React from 'react';
import { Page, Pagination, List } from '../../components/common';
import { MobileRouteProps } from '../router';
import { APIGet, ResData, ReqData } from '../../../config/api';
import { URLParser } from '../../../utils/url';
import { UnregisterCallback } from 'history';
import { Tags } from '../../components/book/tags';
import { HomeNav } from './nav';
import { ThreadPreview } from '../../components/thread/thread-preview';

interface State {
    data:APIGet['/thread']['res']['data'];
    tags:ResData.Tag[]; //fixme:
}

export class Books extends React.Component<MobileRouteProps, State> {
    public state = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
        tags: [],
    };

    public unListen:UnregisterCallback|null = null;

    public componentDidMount () {
        this.loadData();
        this.unListen = this.props.core.history.listen(() => this.loadData());
    }

    public componentWillUnmount () {
        this.unListen && this.unListen();
    }

    public loadData (tags?:number[]) {
        (async () => {
            const url = new URLParser();
            if (url.getAllPath()[0] !== this.props.path) { return; }

            const res = await this.props.core.db.get('/thread', {
                page: url.getQuery('page'),
                tags: tags || url.getQuery('tags'),
                channels: url.getQuery('channels'),
                withType: ReqData.Thread.withType.book,
                ordered: url.getQuery('ordered'),
            });
            if (!res || !res.data) { return; }
            this.setState({data: res.data});

            this.loadNoTongrenTags();
        })();
    }

    public loadNoTongrenTags () {
        (async () => {
            const res = await this.props.core.db.get('/config/noTongrenTags', undefined);
            if (!res || !res.data) { return; }
            this.setState({tags: res.data.tags});
        })();
    }

    public render () {
        return <Page nav={<HomeNav />}>
            <Tags
                tags={this.state.tags}
                search={(pathname, tags) => {
                    this.props.core.history.push(pathname, {tags});
                }}
                getFullList={() => {
                    this.loadNoTongrenTags();
            }} />
            <Pagination
                currentPage={this.state.data.paginate.current_page}
                lastPage={this.state.data.paginate.total_pages}
            />
            <List
                children={this.state.data.threads.map((thread) =>
                    <ThreadPreview data={thread} />)}
            />
        </Page>;
    }
}