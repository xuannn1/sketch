import * as React from 'react';
import { Page, Card, List } from '../../components/common';
import { API } from '../../../config/api';
import { HomeNav } from './nav';
import { MobileRouteProps } from '../router';
import { ThreadPreview } from '../../components/thread/thread-preview';

interface State {
    data:API.Get['/homethread'];
}

export class HomeThread extends React.Component<MobileRouteProps, State> {
    public state:State = {
        data: {},
    };

    public async componentDidMount () {
        const data = await this.props.core.db.getPageHomeThread();
        if (data) {
            this.setState({data});
        }
    }

    public render () {
        const { data } = this.state; 
        const channelIdx = Object.keys(data);
        return <Page nav={<HomeNav />}>
            {channelIdx.map((idx) =>
                <Card title={{
                    text: data[idx].channel.attributes.channel_name,
                    link: `/threads?channels=[${data[idx].channel.id}]`,
                }} key={idx}>
                    {<List children={data[idx].threads.map((thread) => <ThreadPreview mini data={thread} />)} />}
                </Card>)}
        </Page>;
    }
}