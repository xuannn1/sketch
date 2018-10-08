import * as React from 'react';
// import 'bootstrap/js/dist/carousel'; //fixme:
import { Core } from '../../core';
import { Quote } from '../../core/data-types';
import './styles/banner.scss';

interface Props {
    core:Core;
}

interface State {
    quotes:Quote[];
}

export class Banner extends React.Component<Props, State> {
    public state = {
        quotes: [] as Quote[],
    };

    public componentDidMount () {
        this.setState({quotes: this.props.core.db.getQuotes()});
    }

    public renderQuotes () {
        if (this.state.quotes.length === 0) {
            return [];
        }

        return this.state.quotes.map((quote, i) => <div key={i}>
            <div>
                <h2>{ quote.quote }</h2> 
            </div>
            <div>
                <div>
                    { quote.anonymous && quote.majia || <a href="#">{ quote.user_name }</a> } 
                </div>
            </div>
        </div>)
    }

    public render () {
        return <div className="banner">
            <div>
                {/* <Carousel items={this.renderQuotes()} /> */}
                <div>BANNER</div>
            </div>
        </div>;
    }
}

class Carousel extends React.Component<{
    items:JSX.Element[],
}, {}> {
    public render () {
        return <div className="carousel slide" data-ride="carousel" data-interval="5000">
            <div className="carousel-inner">
                { this.props.items.map((el, i) => <div className={`jumbotron carousel-item ${i === 0 ? 'active' : ''}`} key={i}>
                    { el }
                </div>) }
            </div>
            <a className="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                <span className="sr-only">Previous</span>
            </a>
            <a className="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span className="carousel-control-next-icon" aria-hidden="true"></span>
                <span className="sr-only">Next</span>
            </a>
        </div>
    }
}