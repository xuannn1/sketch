import * as React from 'react';
import { ResData } from '../../../config/api';
import { Card } from '../common/card';

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