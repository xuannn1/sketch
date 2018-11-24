import * as React from 'react';
import { Core } from '../../../core';
import { Page } from '../../components/common';

interface Props {
    core:Core;
}

interface State {

}

export class Article extends React.Component<Props, State> {
    public render () {
        return (<Page>
            article
        </Page>);
    }
}