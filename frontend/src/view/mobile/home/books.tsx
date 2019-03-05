import * as React from 'react';
import { Core } from '../../../core';
import { Page } from '../../components/common';
import { APIGet, ResData } from '../../../config/api';
import { BookList } from '../../components/book/book-list';
import { URLParser } from '../../../utils/url';
import { Tags } from '../../components/book/tags';
import { RouteComponentProps } from 'react-router';
import { UnregisterCallback } from 'history';

interface Props extends RouteComponentProps {
    core:Core;
}

interface State {
    data:APIGet['/homebook']['res']['data'];
    tags:APIGet['/config/noTongrenTags']['res']['data']['tags'];
}

export class Books extends React.Component<Props, State> {
    public unListen:UnregisterCallback|null = null;
    public state:State = {
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
        tags: [],
    };

    public componentDidMount () {
        this.loadData();
        this.unListen = this.props.core.history.listen(() => this.loadData());
    }

    public componentWillUnmount () {
        if (this.unListen) {
            this.unListen();
        }
    }

    public render () {
        return (<Page className="books">
            <Tags
                tags={this.state.tags}
                searchTags={(tags) => {
                    const url = new URLParser();
                    if (tags.length === 0) {
                        this.props.core.history.push(
                            url.removeQuery('tags').getPathname(),
                            {tags});
                    } else {
                        this.props.core.history.push(
                            url.setQuery('tags', tags).getPathname(),
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
            const url = new URLParser();
            if (url.getAllPath()[0] !== 'books') { return; }

            const res = await this.props.core.db.get('/homebook', undefined);
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