import * as React from 'react';
import { Card } from '../common';
import { Link } from 'react-router-dom';

interface Props {
}
interface State {
}

export class ChannelList extends React.Component<Props, State> {
    public channelList:{id:number, text:string, tag?:number}[] = [
        {text: '清单', id: 13},
        {text: '问答', id: 14},
        {text: '读写交流', id: 4},
        {text: '日常闲聊', id: 5},
        {text: '随笔', id: 6},
        {text: '非虚构', id: 1},
        {text: '站务公告', id: 7, tag: 39},
        {text: '违规举报', id: 8},
        {text: '投诉仲裁', id: 9},
    ];

    public render () {
        return <div className="channel-list">
            {this.channelList.map((channel, idx) =>
                <Card className="channel" key={idx}>
                    <Link to={`/threads/?channels=[${channel.id}]`}>{channel.text}</Link>
                </Card>)}
        </div>;
    }
}