import * as React from 'react';
import { Card, ShortThread } from '../common';
import { Core } from '../../../core/index';
import { DataType } from '../../../config/data-types';

enum ActiveTab {
    latest,
    best,
}

interface Props {
    core:Core;
}
interface State {
    data:DataType.Home.ThreadCard;
    activeTab:ActiveTab;
}

export class HomeThread extends React.Component<Props, State> {
    public state = {
        data: {
            latest: [],
            best: [],
        },
        activeTab: ActiveTab.latest,
    };

    public async componentDidMount () {
        const res = await this.props.core.db.request('/homeThread');
        if (!res) { return; }

        this.setState({
            data: res.data,
        });
    }

    public render () {
        return <Card>
            <div className="tabs">
                <ul>
                    <li className={this.state.activeTab === ActiveTab.latest ? 'is-active' : ''}
                        onClick={() => this.setState({activeTab: ActiveTab.latest})}>
                        <a><span>最新帖</span></a>
                    </li>
                    <li className={this.state.activeTab === ActiveTab.best ? 'is-active' : ''}
                        onClick={() => this.setState({activeTab: ActiveTab.best})}>
                        <a><span>精华帖</span></a>
                    </li>
                </ul>
            </div>

            { this.state.data[ActiveTab[this.state.activeTab]].map((thread, i) => 
                <ShortThread
                    key={i} 
                    link={'#'}
                    thread={thread}
                    showDetail
                    style={{ marginBottom: '10px' }}
                />
            ) }
        </Card>;
    }
}