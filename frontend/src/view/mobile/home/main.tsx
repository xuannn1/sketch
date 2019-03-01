import * as React from 'react';
import { Core } from '../../../core';
import { Recommendation } from '../../components/thread/recommendation';
import { HomeThread } from '../../components/thread/thread-home';
import { Page, Card } from '../../components/common';
import { Link } from 'react-router-dom';
import { ROUTE } from '../../../config/route';
import { Carousel } from '../../components/carousel';
import { checkType } from '../../../utils/types';
import { APIGet, ResData } from '../../../config/api';
import { RouteComponentProps } from 'react-router';

interface Props {
    core:Core;
}

interface State {
    data:APIGet['/']['res']['data'];
}

export class HomeMain extends React.Component<Props, State> {
    public state:State = {
        data:{
            quotes: [],
            recent_added_chapter_books: [],
            recent_responded_books: [],
            recent_responded_threads: [],
            recent_statuses: [],
        }
    }

    public async componentDidMount () {
        const res = await this.props.core.db.get('/', undefined);
        if (!res) { return; }
        if (checkType(res.data, this.state.data)) {
            this.setState({data: res.data});
        }
    }

    public render () {
        return (<Page>
            <Carousel  
                windowResizeEvent={this.props.core.windowResizeEvent}
                slides={this.state.data.quotes.map((quote, i) => 
                    <span key={i}>{quote.attributes.body}</span>
                )}
                indicator={true} />

            { !this.props.core.user.isLoggedIn() &&
                <Card style={{
                border: 'none',
                backgroundColor: 'transparent',
                textAlign: 'center',
                boxShadow: 'none',
                }}><Link to={ROUTE.login} className="button is-dark">Login</Link></Card>
            }

            {/* <Recommendation recommendations={this.state.data.recommendation} core={this.props.core} /> */}
            <HomeThread latest={this.state.data.recent_responded_threads} />
        </Page>);
    }
}