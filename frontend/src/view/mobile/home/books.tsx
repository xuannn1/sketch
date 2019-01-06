import * as React from 'react';
import { Core } from '../../../core/index';
import { Page } from '../../components/common';
import { BookProfile } from '../../components/book/book-profile';
import { DataType } from '../../../config/data-types';
import { checkType } from '../../../utils/types';
import { BookChapters } from '../../components/book/book-chapters';

export interface HomeBookData {
    profile:DataType.Book.Profile;
    chapters:DataType.Book.ChapterTitle[];
}

interface Props {
    core:Core;
}

interface State {
    data:HomeBookData;
}

export class Books extends React.Component<Props, State> {
    public state = {
        data: {
            profile: DataType.Book.allocProfile(),
            chapters: [],
        }
    }

    public async componentDidMount () {
        const res = await this.props.core.db.request('/books');
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
    }

    public render () {
        return (<Page>
            <BookProfile data={this.state.data.profile} />
            <BookChapters data={this.state.data.chapters} />
        </Page>);
    }
}