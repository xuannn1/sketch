import * as React from 'react';
import { Card } from './common';
import { Core } from '../../core/index';
import { RecommendationShort, HomeRecommendation } from '../../config/data-types';

interface Props {
    core:Core;
}

interface State {
    data:HomeRecommendation;
}

export class SuggestionShort extends React.Component<Props, State> {
    public state:State = {
        data: {
            cards: [],
            long: {title: '', content: '', thread: 0, recommendation: 0},
        }
    }

    public async componentDidMount () {
        const res = await this.props.core.db.request('/homeRecommendation');
        if (!res) { return; }

        this.setState({
            data: res.data,
        })
    }

    public render () {
        return <Card>
            <div className="title" style={{
                textAlign: 'center',
                margin: '5px 0',
            }}>每周推荐</div>

            <div className="main">
                <div className="container-cards" style={{
                    
                }}>
                    { this.state.data.cards.map((card, i) => <div className="recommendation-card" key={i}>
                        <div className="title">{ card.title }</div>
                        <div className="content">{ card.content }</div>
                    </div>)}
                </div>

                <div className="container-long">
                    <div className="title">{this.state.data.long.title}</div>
                    <div className="content">{this.state.data.long.content}</div>
                </div>
            </div>
        </Card>;
    }
}