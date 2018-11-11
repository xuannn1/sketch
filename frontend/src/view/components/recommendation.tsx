import * as React from 'react';
import { Card, ShortThread } from './common';
import { Core } from '../../core/index';
import { HomeRecommendation } from '../../config/data-types';
import { Styles } from '../../utils/types';
import { timingSafeEqual } from 'crypto';

interface Props {
    core:Core;
}

interface State {
    data:HomeRecommendation;
}

export class Recommendation extends React.Component<Props, State> {
    public cardWrapperEl:HTMLDivElement|null = null;
    public CARD_MIN_WIDTH = 200; //px

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

        this.props.core.windowResizeEvent.sub((undefined) => this.forceUpdate());
    }

    public render () {
        const cardWrapperWidth = this.cardWrapperEl && this.cardWrapperEl.offsetWidth || 1;
        const { cards, long } = this.state.data;
        const overflowX = cardWrapperWidth >= cards.length * this.CARD_MIN_WIDTH ? 'auto' : 'scroll';

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
                overflowX: 'auto',
            },
            cardsContainer: {
                width: `${this.state.data.cards.length / Math.ceil(cardWrapperWidth / this.CARD_MIN_WIDTH) * 100}%`,
                backgroundColor: 'white',
                display: 'flex',
                minHeight: '130px',
            },
            card: {
                flex: 1,
                padding: '0px 5px 5px 5px',
                marginRight: '10xp',
            },
            long: {
    
            },
        }

        return <Card style={s.main}>
            <div style={s.heading}>每周推荐</div>
            <div>
                <div style={s.cardsWrapper} ref={(el) => this.cardWrapperEl = el}>
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