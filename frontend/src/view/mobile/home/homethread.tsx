import * as React from 'react';
import { Page, Card, List } from '../../components/common';
import { APIGet } from '../../../config/api';
import { HomeNav } from './nav';
import { MobileRouteProps } from '../router';
import { ThreadPreview } from '../../components/thread/thread-preview';

interface State {
    data:APIGet['/homethread']['res']['data'];
}

export class HomeThread extends React.Component<MobileRouteProps, State> {
    public state:State = {
        data: {},
    };

    public async componentDidMount () {
        const res = await this.props.core.db.get('/homethread', undefined); 
        if (!res || !res.data) { return; }
        this.setState({data: res.data});
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