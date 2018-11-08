import * as React from 'react';
import { Card, ShortThread } from './common';
import { Core } from '../../core/index';
import { HomeRecommendation } from '../../config/data-types';
import { Styles } from '../../utils/types';

interface Props {
    core:Core;
}

interface State {
    data:HomeRecommendation;
}

export class Recommendation extends React.Component<Props, State> {
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
        });
    }

    public render () {
        const s:Styles = {
            main: {
            },
            heading: {
                textAlign: 'center',
                margin: '5px 0',
                lineHeight: '2em',
                fontWeight: 'bold',
                fontSize: '150%',
            },
            cardsWrapper: {
                overflowX: 'scroll',
            },
            cardsContainer: {
                width: `${this.state.data.cards.length / 2}00%`,
                display: 'flex',
                overflowX: 'scroll',
                minHeight: '130px',
            },
            card: {
                flex: 1,
            },
            long: {
    
            },
        }

        return <Card style={s.main}>
            <div style={s.heading}>每周推荐</div>
            <div>
                <div style={s.cardsWrapper}>
                    <div style={s.cardsContainer}>
                        { this.state.data.cards.map((card, i) => 
                            <ShortThread
                                style={s.card}
                                key={i}
                                link={'#'}
                                title={card.title}
                                content={card.content} />
                        )}
                    </div> 
                </div>

                <ShortThread
                    style={s.long}
                    link={'#'}
                    title={this.state.data.long.title}
                    content={this.state.data.long.content} />
            </div>
        </Card>;
    }
}