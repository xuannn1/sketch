import * as React from 'react';
import { List } from './list';
import { Link } from 'react-router-dom';
import { Card } from './card';
import './tab.scss';
import { classnames } from '../../../utils/classname';

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
    const tab = this.props.tabs[this.state.onTab];

    return <Card className={classnames('tab-card', this.props.className)}>
      <div className="tabs is-boxed">
        <ul>
          {this.props.tabs.map((tab, i) =>
            <li key={i}
              onClick={() => this.setState({onTab: i})}
              className={classnames({'is-active': this.state.onTab === i})}>
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