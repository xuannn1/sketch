import * as React from 'react';
import { Core } from '../../../core';
import { Banner } from '../../components/banner';
import { SuggestionShort } from '../../components/suggestion-short';
import { ThreadShort } from '../../components/thread-short';
import { Page } from '../../components/common';

interface Props {
    core:Core;
}

interface State {

}

export class HomeDefault_m extends React.Component<Props, State> {
    public render () {
        return (<Page>
            <Banner core={this.props.core} />
            <SuggestionShort />
            <ThreadShort />
        </Page>);
    }
}