import * as React from 'react';
import { ResData } from '../../../config/api';
import { Card } from '../common';

interface Props {
    data:ResData.Thread;
}

interface State {
}

export class ThreadProfile extends React.Component<Props, State> {
    public render () {
        return <Card>
        </Card>;
    }
}