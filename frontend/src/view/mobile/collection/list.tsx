import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page, Pagination, Card, List } from '../../components/common';
import { CollectionNav } from './nav';
import { APIGet, ReqData, ResData } from '../../../config/api';
import { ThreadPreview } from '../../components/thread/thread-preview';


interface State {
    data:APIGet['/collection']['res']['data'];
}

export class CollectionList extends React.Component<MobileRouteProps, State> {
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
    };

    public async componentDidMount () {
        const res = await this.props.core.db.get('/collection', {
            withType: ReqData.Collection.Type.list,
        });
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
    }

    public render () {
        const { data } = this.state;
        return (<Page nav={<CollectionNav />}>
            <Pagination currentPage={data.paginate.current_page} lastPage={data.paginate.total_pages} />
            <Card>
                <List children={data.threads.map((thread) => <ThreadPreview data={thread} />)} />
            </Card>
        </Page>);
    }
}