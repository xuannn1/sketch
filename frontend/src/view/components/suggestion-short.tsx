import * as React from 'react';
import { MyCard } from './common';

interface Props {
}
interface State {
}

export class SuggestionShort extends React.Component<Props, State> {
    public render () {
        return <MyCard>
            suggestions
        </MyCard>;
    }
}