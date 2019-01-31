import * as React from 'react';
import { Core } from '../../../core';
import { Page } from '../../components/common';
import { APIGet, ResData } from '../../../config/api';
import { BookList } from '../../components/book/book-list';

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
        const res = await this.props.core.db.get('/thread', {
            withType: 'book',
        });
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
    }

    public render () {
        return (<Page className="books">
            <div className="thread-form">
                {this.state.data.threads.map((thread, idx) => {
                    return <BookList
                        author={thread.author.attributes.name}
                        brief={thread.attributes.brief || ''}
                        title={thread.attributes.title || ''}
                        latestChapter={''}
                        tags={thread.tags || []}
                    />;
                })}
            </div>
        </Page>);
    }
}