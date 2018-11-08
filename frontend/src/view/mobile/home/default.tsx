import * as React from 'react';
import { Core } from '../../../core';
import { Recommendation } from '../../components/recommendation';
import { ThreadShort } from '../../components/thread-short';
import { Page, Card } from '../../components/common';
import { Link } from 'react-router-dom';
import { ROUTE } from '../../../config/route';
import { Carousel } from '../../components/carousel';

interface Props {
    core:Core;
}

interface State {

}

export class HomeDefault_m extends React.Component<Props, State> {
    public render () {
        return (<Page>
            <Carousel slides={[
                <span>one</span>,
                <span>two</span>,
                <span>three</span>,
            ]}
                indicator={true} />

            { !this.props.core.user.isLoggedIn() &&
                <Card style={{
                border: 'none',
                backgroundColor: 'transparent',
                textAlign: 'center',
                boxShadow: 'none',
                }}><Link to={ROUTE.login} className="button is-dark">Login</Link></Card>
            }

            <Recommendation core={this.props.core} />
            <ThreadShort />
        </Page>);
    }
}