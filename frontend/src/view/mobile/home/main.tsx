import * as React from 'react';
import { Link } from 'react-router-dom';
import { Quotes } from '../../components/home/quotes';
import { API } from '../../../config/api';
import { HomeMenu } from './home-menu';
import { MobileRouteProps } from '../router';
import { BookPreview } from '../../components/home/book-preview';
import { ThreadPreview } from '../../components/thread/thread-preview';
import { StatusPreview } from '../../components/home/status-preview';
import { Page } from '../../components/common/page';
import { Card } from '../../components/common/card';
import { Tab } from '../../components/common/tab';
import { MainMenu } from '../main-menu';
interface State {
  data:API.Get['/'];
}

export class HomeMain extends React.Component<MobileRouteProps, State> {
  public state:State = {
    data:{
      quotes: [],
      recent_added_chapter_books: [],
      recent_responded_books: [],
      recent_responded_threads: [],
      recent_statuses: [],
    },
  };

  public async componentDidMount () {
    const data = await this.props.core.db.getPageHome();
    if (data) {
      this.setState({data});
    }
  }

  public render () {
    return (<Page
      bottom={<MainMenu />}
      top={<HomeMenu />} >
      <Quotes
        quotes={this.state.data.quotes}
        core={this.props.core}
      />
      { !this.props.core.user.isLoggedIn() &&
        <Card style={{
        border: 'none',
        backgroundColor: 'transparent',
        textAlign: 'center',
        boxShadow: 'none',
        }}><Link to={'/login'} className="button is-dark">Login</Link></Card>
      }

      {/* <Recommendation recommendations={this.state.data.recommendation} core={this.props.core} /> */}
    </Page>);
  }
}