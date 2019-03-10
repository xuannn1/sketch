import * as React from 'react';
import { ResData, APIGet } from '../../../config/api';
import { Page, Pagination } from '../../components/common';
import { Topnav } from '../../components/topnav';
import { URLParser } from '../../../utils/url';
import { MobileRouteProps } from '../router';
import { ThreadProfile } from '../../components/thread/thread-profile';
import { Post } from '../../components/post/post';


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
        const id = this.props.match.params.id;

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
            <ThreadProfile data={thread} />
            {posts.map((post, idx) => <Post data={post} key={idx} />)}
            <Pagination currentPage={paginate.current_page} lastPage={paginate.total_pages} />
        </Page>;
    }
}