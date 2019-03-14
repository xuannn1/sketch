/*
import * as React from 'react';
import { Card, ShortThread } from '../common';
import { Styles } from '../../../utils/types';
import { ResData } from '../../../config/api';
import { EventBus } from '../../../utils/events';

interface Props {
    windowResizeEvent:EventBus<void>;
    recommendations:ResData.Recommendation[];
}

interface State {
}

export class Recommendation extends React.Component<Props, State> {
    public cardWrapperEl:HTMLDivElement|null = null;
    public CARD_MIN_WIDTH = 200; //px

    public componentDidMount () {
        this.props.windowResizeEvent.sub(() => this.forceUpdate());
    }

    public render () {
        const cardWrapperWidth = this.cardWrapperEl && this.cardWrapperEl.offsetWidth || 1;
        const shots:ResData.Recommendation[] = [];
        let longs:ResData.Recommendation[] = [];

        for (let i = 0; i < this.props.recommendations.length; i ++) {
            const recom = this.props.recommendations[i];
            if (recom.attributes.type === 'long') {
                longs.push(recom);
            }
            if (recom.attributes.type === 'shot') {
                shots.push(recom);
            }
        }

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
                width: `${shots.length
                    && shots.length / Math.ceil(cardWrapperWidth / this.CARD_MIN_WIDTH) * 100
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
                        { shots.map((recom, i) => 
                            <ShortThread
                                style={s.card}
                                key={i}
                                link={'#'}
                                thread={{
                                    id: recom.id,
                                    type: 'thread',
                                    attributes: {
                                        title: recom.attributes.brief,
                                        body: recom.attributes.body,
                                        created_at: recom.attributes.created_at,
                                    },
                                    author: recom.authors[0],
                                }} />
                        )}
                    </div> 
                </div>

                {longs.length > 0 &&
                    <ShortThread
                    style={s.long}
                    link={'#'}
                    thread={{
                        id: longs[0].id,
                        type: 'thread',
                        attributes: {
                            title: longs[0].attributes.brief,
                            body: longs[0].attributes.body,
                            created_at: longs[0].attributes.created_at,
                        },
                        author: longs[0].authors[0],
                    }} /> 
                }
            </div>
        </Card>;
    }
}
*/