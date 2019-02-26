import * as React from 'react';
import { Core } from '../../../core';
import { Page, Pagination } from '../../components/common';
import { APIGet, ResData } from '../../../config/api';
import { BookList } from '../../components/book/book-list';
import { parseArrayQuery } from '../../../utils/url';

interface Props {
    core:Core;
}

interface State {
    data:APIGet['/thread']['res']['data'];
}

export class Books extends React.Component<Props, State> {
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
    };

    public async componentDidMount () {
        const url = new URL(window.location.href);
        const page = url.searchParams.get('page');

        const res = await this.props.core.db.get('/thread', {
            withType: 'book',
            tags: parseArrayQuery(window.location.href, 'tag'),
            page: page && +page || undefined,
        });
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
    }

    public render () {
        return (<Page className="books">
            <Pagination currentPage={this.state.data.paginate.current_page} lastPage={this.state.data.paginate.total_pages} />
            <div className="thread-form">
                {this.state.data.threads.map((thread, idx) => 
                    <BookList thread={thread} key={idx} />,
                )}
            </div>
        </Page>);
    }
}