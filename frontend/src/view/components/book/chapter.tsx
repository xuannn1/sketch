import * as React from 'react';
import { Card } from '../common';
import { ResData } from '../../../config/api';

interface Props {
    chapter:ResData.Chapter;
}

interface State {
}

export class BookChapterContent extends React.Component<Props, State> {
    public render () {
        return <Card>
            {/* top bar: back, share, favourite, download */}
            {/* title */}
            {/* brief */}
            {/* username */}
            {/* created_at updated_at */}
            {/* chars views reply_count */}
            {/* body */}
            {/* thread mode */}
            {/* prev love reply next */}
            {/* posts */}
            {/* bottom bar: dir, settings */}
        </Card>;
    }
}
