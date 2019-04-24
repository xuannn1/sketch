import * as React from 'react';
import { ResData } from '../../../config/api';

interface Props {
  status:ResData.Status;
}
interface State {
}

export class StatusPreview extends React.Component<Props, State> {
  public render () {
    return <div>
      {this.props.status.attributes.body}
    </div>
  }
}