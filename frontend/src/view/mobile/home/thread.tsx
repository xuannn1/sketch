import * as React from 'react';
import { ResData, APIGet } from '../../../config/api';
import { Page, Pagination } from '../../components/common';
import { Topnav } from '../../components/topnav';
import { URLParser } from '../../../utils/url';
import { MobileRouteProps } from '../router';


interface State {
    data:APIGet['/thread/:id']['res']['data'];
}

export class Thread extends React.Component<MobileRouteProps, State> {
    public state = {
        data: {
            thread: ResData.allocThread(),
            paginate: ResData.allocThreadPaginate(),
            posts: [] as ResData.Post[],
        },
    };

    public async componentDidMount () {
        const url = new URLParser();
        const id = url.getAllPath()[1];

        const res = await this.props.core.db.get('/thread/:id', {
            id: +id,
            page: url.getQuery('page'),
        });
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
    }
    public render () {
        const { thread, paginate, posts } = this.state.data;

        return <Page nav={<Topnav core={this.props.core}
                center={thread.attributes.title} /> }>
            {thread.attributes.title}
            {thread.attributes.body}
            {posts.map((post) => <span>{post.attributes.title}</span>)}
            <Pagination currentPage={paginate.current_page} lastPage={paginate.total_pages} />
        </Page>;
    }
}