import * as React from 'react';
import { Core } from '../../../core/index';
import { Page } from '../../components/common';
import { URLParser } from '../../../utils/url';
import { UnregisterCallback } from 'history';
import { ChannelList } from '../../components/thread/channel-list';
import { APIGet, ResData } from '../../../config/api';
import { ThreadList } from '../../components/thread/thread-list';
import { HomeTopNav } from './homenav';
import { MobileRouteProps } from '../router';

interface State {
    showChannel:number;
    data:APIGet['/thread']['res']['data'];
}

export class HomeThread extends React.Component<MobileRouteProps, State> {
    public unListen:UnregisterCallback|null = null;

    public state = {
        showChannel: 0,
        data: {
            threads: [],
            paginate: ResData.allocThreadPaginate(),
        },
    };

    public componentDidMount () {
        this.unListen = this.props.core.history.listen(() => this.loadData());
        this.loadData();
    }
    public componentWillUnmount () {
        this.unListen && this.unListen();
    }

    public render () {
    return (<Page nav={<HomeTopNav />}>
            {this.state.showChannel === 0 ?
                <ChannelList /> :
                this.renderChannelThreads()}
        </Page>);
    }

    public renderChannelThreads () {
        return <div>
            <ThreadList
                threads={this.state.data.threads}
                paginate={this.state.data.paginate}
                channelId={this.state.showChannel}
            />
        </div>;
    }

    public loadData () {
        (async () => {
            const url = new URLParser();
            if (url.getAllPath()[0] !== 'threads') { return; }
            const channel = url.getQuery('channels');
            if (!channel) {
                this.setState({ showChannel: 0 });
                return;
            }

            const res = await this.props.core.db.get('/thread', {
                withType: 'thread',
                channels: channel,
                tags: url.getQuery('tags'),
            });
            if (!res || !res.data) { return; }
            this.setState({
                showChannel: channel[0],
                data: res.data,
            });
        })();
    }
}