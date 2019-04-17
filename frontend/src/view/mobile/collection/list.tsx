import * as React from 'react';
import { MobileRouteProps } from '../router';
import { Page, Pagination, Card, List } from '../../components/common';
import { CollectionNav } from './nav';
import { ReqData, ResData, API } from '../../../config/api';
import { ThreadPreview } from '../../components/thread/thread-preview';


interface State {
    data:API.Get['/collection'];
}

export class CollectionList extends React.Component<MobileRouteProps, State> {
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
    };

    public async componentDidMount () {
        const data = await this.props.core.db.getCollection(ReqData.Collection.type.list);
        if (data) {
            this.setState({data});
        }
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