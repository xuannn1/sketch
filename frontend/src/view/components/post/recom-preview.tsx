import * as React from 'react';
import { ResData } from '../../../config/api';

interface Props {
    data:ResData.Post;
}
interface State {
}

export class RecomPreview extends React.Component<Props, State> {
    public render () {
        return <div>
            {this.props.data.attributes.body}
        </div>;
    }
}