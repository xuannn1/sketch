import * as React from 'react';
import { Card, ShortThread } from '../common';
import { DataType } from '../../../config/data-types';
import { Styles } from '../../../utils/types';
import { Core } from '../../../core/index';

export function allocRecommendationData () : RecommendationData {
    return {
        cards: [],
        long: DataType.Home.allocRecommendation(),
    }
}

export interface RecommendationData {
    cards:DataType.Home.Recommendation[];
    long:DataType.Home.Recommendation;
}

interface Props {
    core:Core;
    data:RecommendationData;
}

interface State {
}

export class Recommendation extends React.Component<Props, State> {
    public cardWrapperEl:HTMLDivElement|null = null;
    public CARD_MIN_WIDTH = 200; //px

    public componentDidMount () {
        this.props.core.windowResizeEvent.sub(() => this.forceUpdate());
    }

    public render () {
        const cardWrapperWidth = this.cardWrapperEl && this.cardWrapperEl.offsetWidth || 1;
        const { cards, long } = this.props.data;

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
                width: `${cards.length
                    && cards.length / Math.ceil(cardWrapperWidth / this.CARD_MIN_WIDTH) * 100
                    || 100}%`,
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
                        { cards.map((card, i) => 
                            <ShortThread
                                style={s.card}
                                key={i}
                                link={'#'}
                                thread={{
                                    id: card.id,
                                    title: card.title,
                                    content: card.content,
                                }} />
                        )}
                    </div> 
                </div>

                <ShortThread
                    style={s.long}
                    link={'#'}
                    thread={{
                        id: long.id,
                        title: long.title,
                        content: long.content,
                    }} />
            </div>
        </Card>;
    }
}