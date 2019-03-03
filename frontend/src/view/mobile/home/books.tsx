import * as React from 'react';
import { Core } from '../../../core';
import { Page, Pagination } from '../../components/common';
import { APIGet, ResData } from '../../../config/api';
import { BookList } from '../../components/book/book-list';
import { parseArrayQuery } from '../../../utils/url';
import { Tags } from '../../components/book/tags';

interface Props {
    core:Core;
}

interface State {
    data:APIGet['/thread']['res']['data'];
    tags:APIGet['/config/noTongrenTags']['res']['data']['tags'];
    fullListTags:boolean;
}

export class Books extends React.Component<Props, State> {
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
        tags: [],
        fullListTags: false,
    };

    public componentDidMount () {
        this.loadData();
        this.props.core.history.listen(() => this.loadData());
    }

    public render () {
        return (<Page className="books">
            <Tags
                tags={this.state.tags}
                selectedTags={parseArrayQuery(window.location.href, 'tags')}
                getFullList={() => this.loadTongrenTags()} />
            <BookList
                threads={this.state.data.threads}
                paginate={this.state.data.paginate} />
        </Page>);
    }

    public loadData () {
        (async () => {
            const url = new URL(window.location.href);
            const page = url.searchParams.get('page');

            const res = await this.props.core.db.get('/thread', {
                withType: 'book',
                tags: parseArrayQuery(window.location.href, 'tags'),
                page: page && +page || undefined,
            });
            if (!res || !res.data) { return; }
            this.setState({data: res.data});
        })();
    }

    public loadTongrenTags () {
        (async () => {
            const res = await this.props.core.db.get('/config/noTongrenTags', undefined);
            if (!res || !res.data) { return; }
            this.setState({tags: res.data.tags});
        })();
    }
}