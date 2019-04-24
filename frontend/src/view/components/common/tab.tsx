import * as React from 'react';
import { List } from './list';
import { Link } from 'react-router-dom';
import { Card } from './card';
import './tab.scss';

export class Tab extends React.Component<{
  tabs:{name:string, children:React.ReactNode[], more?:string}[];
  className?:string;
}, {
  onTab:number;
}> {
  public state = {
    onTab: 0,
  }

  public render () {
    const className = this.props.className || '';
    const tab = this.props.tabs[this.state.onTab];

    return <Card className={`tab-card ${className}`}>
      <div className="tabs is-boxed">
        <ul>
          {this.props.tabs.map((tab, i) =>
            <li key={i}
              onClick={() => this.setState({onTab: i})}
              className={this.state.onTab === i ? 'is-active' : ''}>
              <a><span>{tab.name}</span></a>
            </li>)}
        </ul>
      </div>
      <div className="tab-content">
        <List children={tab.children} />
      </div>
      { tab.more &&
        <Link className="more" to={tab.more}>
          更多
        </Link>
      }
    </Card>;
  }
}