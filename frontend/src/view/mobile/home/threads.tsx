import * as React from 'react';
import { Page, Pagination, Card, List } from '../../components/common';
import { HomeNav } from './nav';
import { MobileRouteProps } from '../router';
import { APIGet, ResData, ReqData } from '../../../config/api';
import { URLParser } from '../../../utils/url';
import { ThreadPreview } from '../../components/thread/thread-preview';
import { UnregisterCallback } from 'history';

interface State {
    data:APIGet['/thread']['res']['data'];
}

export class Threads extends React.Component<MobileRouteProps, State> {
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
    };

    public unlisten:UnregisterCallback|null = null;
    public componentDidMount () {
        this.loadData();
        this.props.core.history.listen(() => this.loadData())
    }

    public componentWillUnmount () {
        this.unlisten && this.unlisten();
    }

    public render () {
        const { data } = this.state;
        return <Page nav={<HomeNav />}>
            <Pagination currentPage={data.paginate.current_page} lastPage={data.paginate.total_pages} />
            <Card>
                <List children={data.threads.map((thread) =>
                    <ThreadPreview data={thread} />)} />
            </Card>
        </Page>;
    }

    public loadData () {
        (async () => {
            const url = new URLParser();
            if (url.getAllPath()[0] !== this.props.path) { return; }

            const res = await this.props.core.db.get('/thread', {
                page: url.getQuery('page'),
                tags: url.getQuery('tags'),
                channels: url.getQuery('channels'),
                withType: ReqData.Thread.withType.thread,
                ordered: url.getQuery('ordered'),
            });
            if (!res || !res.data) { return; }
            this.setState({data: res.data});
        })();
    }
}