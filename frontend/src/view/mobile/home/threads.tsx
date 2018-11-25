import * as React from 'react';
import { Core } from '../../../core/index';
import { Page } from '../../components/common';

interface Props {
    core:Core;
}

interface State {

}

export class Threads extends React.Component<Props, State> {
    public render () {
        return (<Page>
            threads
        </Page>);
    }
}