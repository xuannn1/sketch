import * as React from 'react';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { Quote } from '../../core/data-types';

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
        return <div className="">
            <div className="">
                { Carousel(this.renderQuotes()) }
            </div>
        </div>;
    }
}

function Carousel (props:JSX.Element[]) {
    return <div className="carousel slide" data-ride="carousel">
        <div className="carousel-inner">
            { props.map((el, i) => <div className={`jumbotron item ${i === 0 ? 'active' : ''}`} key={i}>
                { el }
            </div>) }
        </div>
        <a className="left carousel-control" href="#myCarousel" data-slide="prev">
            <span className="glyphicon glyphicon-chevron-left"></span>
            <span className="sr-only">Previous</span>
            </a>
            <a className="right carousel-control" href="#myCarousel" data-slide="next">
            <span className="glyphicon glyphicon-chevron-right"></span>
            <span className="sr-only">Next</span>
            </a>
    </div>
}