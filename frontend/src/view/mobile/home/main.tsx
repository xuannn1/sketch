import * as React from 'react';
import { HomeThread } from '../../components/thread/thread-home';
import { Page, Card } from '../../components/common';
import { Link } from 'react-router-dom';
import { Carousel } from '../../components/carousel';
import { checkType } from '../../../utils/types';
import { APIGet } from '../../../config/api';
import { HomeTopNav } from './homenav';
import { MobileRouteProps } from '../router';
interface State {
    data:APIGet['/']['res']['data'];
}

export class HomeMain extends React.Component<MobileRouteProps, State> {
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
        return (<Page nav={<HomeTopNav />}>
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
                }}><Link to={'/login'} className="button is-dark">Login</Link></Card>
            }

            {/* <Recommendation recommendations={this.state.data.recommendation} core={this.props.core} /> */}
            <HomeThread latest={this.state.data.recent_responded_threads} />
        </Page>);
    }
}