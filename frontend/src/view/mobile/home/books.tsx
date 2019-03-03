import * as React from 'react';
import { Core } from '../../../core';
import { Page } from '../../components/common';
import { APIGet, ResData } from '../../../config/api';
import { BookList } from '../../components/book/book-list';
import { parseArrayQuery as getArrayQuery, addQuery, removeQuery } from '../../../utils/url';
import { Tags } from '../../components/book/tags';
import { RouteComponentProps } from 'react-router';

interface Props extends RouteComponentProps {
    core:Core;
}

interface State {
    data:APIGet['/thread']['res']['data'];
    tags:APIGet['/config/noTongrenTags']['res']['data']['tags'];
}

export class Books extends React.Component<Props, State> {
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
        tags: [],
    };

    public componentDidMount () {
        this.loadData();
        this.props.core.history.listen(() => this.loadData());
    }

    public render () {
        return (<Page className="books">
            <Tags
                tags={this.state.tags}
                searchTags={(tags) => {
                    if (tags.length === 0) {
                        this.props.core.history.push(
                            removeQuery(window.location.href, 'tags'),
                            {tags});
                    } else {
                        const queryValue = '[' + tags.join(',') + ']';
                        this.props.core.history.push(
                            addQuery(window.location.href, 'tags', queryValue),
                            {tags});
                    }
                }}
                getFullList={() => {
                    this.loadNoTongrenTags();
                }} />
            <BookList
                threads={this.state.data.threads}
                paginate={this.state.data.paginate} />
        </Page>);
    }

    public loadData (tags?:number[]) {
        (async () => {
            const url = new URL(window.location.href);
            const page = url.searchParams.get('page');

            const res = await this.props.core.db.get('/thread', {
                withType: 'book',
                tags: tags || getArrayQuery(window.location.href, 'tags'),
                channels: getArrayQuery(window.location.href, 'channels'),
                page: page && +page || undefined,
            });
            if (!res || !res.data) { return; }
            this.setState({data: res.data});
        })();
    }

    public loadNoTongrenTags () {
        (async () => {
            const res = await this.props.core.db.get('/config/noTongrenTags', undefined);
            if (!res || !res.data) { return; }
            this.setState({tags: res.data.tags});
        })();
    }
}