import * as React from 'react';
import { Card } from '../common';
import { ResData } from '../../../config/api';

interface Props {
    data:ResData.Post;
}
interface State {
}

export class Post extends React.Component<Props, State> {
    public render () {
        return <Card>

        </Card>;
    }
}