import * as React from 'react';
import { Core } from '../../../core';
import { Recommendation, RecommendationData, allocRecommendationData } from '../../components/thread/recommendation';
import { HomeThread, HomeThreadData, allocHomeThreadData } from '../../components/thread/thread-short';
import { Page, Card } from '../../components/common';
import { Link } from 'react-router-dom';
import { ROUTE } from '../../../config/route';
import { Carousel } from '../../components/carousel';
import { checkType } from '../../../utils/types';

export interface HomeMainData {
    recommendation:RecommendationData;
    thread:HomeThreadData;
}

interface Props {
    core:Core;
}

interface State {
    data:HomeMainData;
}

export class HomeMain extends React.Component<Props, State> {
    public state = {
        data:{
            recommendation: allocRecommendationData(),
            thread: allocHomeThreadData(), 
        }
    }

    public async componentDidMount () {
        const res = await this.props.core.db.post('/home');
        if (!res) { return; }
        if (checkType(res.data, this.state.data)) {
            this.setState({data: res.data});
        }
    }

    public render () {
        return (<Page>
            <Carousel core={this.props.core} slides={[
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

            <Recommendation data={this.state.data.recommendation} core={this.props.core} />
            <HomeThread data={this.state.data.thread} />
        </Page>);
    }
}