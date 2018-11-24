import * as React from 'react';
import { Card, ShortThread } from '../common';
import { DataType } from '../../../config/data-types';

enum ActiveTab {
    latest,
    best,
}

export function allocHomeThreadData () : HomeThreadData {
    return {
        latest: [],
        best: [],
    };
}

export interface HomeThreadData {
    latest:DataType.Home.Thread[];
    best:DataType.Home.Thread[];
}

interface Props {
    data:HomeThreadData;
}

interface State {
    activeTab:ActiveTab;
}

export class HomeThread extends React.Component<Props, State> {
    public state = {
        activeTab: ActiveTab.latest,
    };

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

            { this.props.data[ActiveTab[this.state.activeTab]].map((thread, i) => 
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