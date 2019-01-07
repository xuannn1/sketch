import * as React from 'react';
import { Card, ShortThread } from '../common';
import { ResData } from '../../../config/api';

enum ActiveTab {
    latest,
    best,
}

interface Props {
    latest:ResData.Thread[];
    best?:ResData.Thread[];
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
                    { this.props.best &&
                        <li className={this.state.activeTab === ActiveTab.best ? 'is-active' : ''}
                            onClick={() => this.setState({activeTab: ActiveTab.best})}>
                            <a><span>精华帖</span></a>
                        </li> 
                    }
                </ul>
            </div>

            { this.props[ActiveTab[this.state.activeTab]].map((thread, i) => 
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