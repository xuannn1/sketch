import * as React from 'react';
import { HomeTopNav } from './homenav';
import { Page } from '../../components/common';
import { MobileRouteProps } from '../router';
import { APIGet, ResData } from '../../../config/api';
import { URLParser } from '../../../utils/url';
import { UnregisterCallback } from 'history';
import { Tags } from '../../components/book/tags';
import { BookList } from '../../components/book/book-list';

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
                withType: 'book',
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
        return <Page nav={<HomeTopNav />}>
            <Tags
                tags={this.state.tags}
                search={(pathname, tags) => {
                    this.props.core.history.push(pathname, {tags});
                }}
                getFullList={() => {
                    this.loadNoTongrenTags();
            }} />
            <BookList
                threads={this.state.data.threads}
                paginate={this.state.data.paginate}
            />
        </Page>;
    }
}