import * as React from 'react';
import { Core } from '../../../core';
import { Banner } from '../../components/banner';
import { SuggestionShort } from '../../components/suggestion-short';
import { ThreadShort } from '../../components/thread-short';
import { Page, Card } from '../../components/common';
import { Link } from 'react-router-dom';
import { ROUTE } from '../../../config/route';

interface Props {
    core:Core;
}

interface State {

}

export class HomeDefault_m extends React.Component<Props, State> {
    public render () {
        return (<Page>
            <Banner core={this.props.core} />

            { !this.props.core.user.isLoggedIn() &&
                <Card style={{
                border: 'none',
                textAlign: 'center',
                boxShadow: 'none',
                }}><Link to={ROUTE.login} className="button is-dark">Login</Link></Card>
            }

            <SuggestionShort />
            <ThreadShort />
        </Page>);
    }
}