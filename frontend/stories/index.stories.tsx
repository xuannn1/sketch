import * as React from 'react';
import { storiesOf, addDecorator } from '@storybook/react';
import { Carousel } from '../src/view/components/carousel';
import { withViewport } from '@storybook/addon-viewport';
import { withConsole } from '@storybook/addon-console';
import { withKnobs, boolean } from '@storybook/addon-knobs';
import { Core } from '../src/core';
// import { Chapter } from '../src/view/components/book/chapter';
import { HomeThread } from '../src/view/components/thread/thread-home';
import '../src/theme.scss';
import { pageDecorator } from './decorator';

// import { action } from '@storybook/addon-actions';

const core = new Core();

addDecorator((storyFn, context) => withConsole()(storyFn)(context));

storiesOf('Home', module)
    .addDecorator(withViewport())
    .addDecorator(withKnobs)
    .addDecorator(pageDecorator)
    .add('Carousel', () => 
        <Carousel
            windowResizeEvent={core.windowResizeEvent}
            slides={[
                <span>one</span>,
                <span>two</span>,
                <span>three</span>,
            ]}
            indicator={boolean(true)} />
    )
    .add('Home Thread', () =>
        <HomeThread
            latest={(new Array(5)).fill({
                "type": "thread",
                "id": 8,
                "attributes": {
                    "title": "Consequatur porro veniam nihil molestias quos qui.",
                    "is_anonymous": false,
                    "majia": ""
                },
                "author": {
                    "type": "user",
                    "id": 5,
                    "attributes": {
                        "name": "Abelardo Ortiz PhD"
                    }
                },
                "channel": {
                    "type": "channel",
                    "id": 4,
                    "attributes": {
                        "channel_name": "读写交流"
                    }
                },
                "tags": []
            })}
        />
    )
;

// storiesOf('Book', module)
//     .addDecorator(withViewport())
//     .addDecorator(withKnobs)
//     .add('Chapter', () =>
//         <Chapter chapter={{
//             type: 'chapter',
//             id: 0,
//             attributes: {
//                 title: '',
//                 body: '',
//             }
//         }} /> 
//     )
// ;
