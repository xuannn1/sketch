import * as React from 'react';
import { ResData } from '../../../config/api';
import { Link } from 'react-router-dom';

interface Props {
    data:ResData.Post;
}
interface State {
}

export class PostPreview extends React.Component<Props, State> {
    public render () {

        const {attributes, id} = this.props.data;
        return <div>
            <Link className="title" to={`/thread/${id}`}>{ attributes.title }</Link>
            <div className="biref">
                {attributes.body}
            </div>    
        </div>;
    }
}