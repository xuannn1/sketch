import * as React from 'react';
import { MyCard } from './common';

interface Props {
}
interface State {
}

export class ThreadShort extends React.Component<Props, State> {
    public render () {
        return <MyCard>
            Forum
        </MyCard>;
    }
}