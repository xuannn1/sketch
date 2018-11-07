import * as React from 'react';
import { storiesOf, addDecorator } from '@storybook/react';
import { Carousel } from '../src/view/components/carousel';
import { withViewport } from '@storybook/addon-viewport';
import { withConsole } from '@storybook/addon-console';
import { withKnobs, boolean } from '@storybook/addon-knobs';
import { Core } from '../src/core';
import { SuggestionShort } from '../src/view/components/suggestion-short';

// import { action } from '@storybook/addon-actions';

const core = new Core();

addDecorator((storyFn, context) => withConsole()(storyFn)(context));

storiesOf('Home', module)
    .addDecorator(withViewport())
    .addDecorator(withKnobs)
    .add('Carousel', () => 
        <Carousel slides={[
            <span>one</span>,
            <span>two</span>,
            <span>three</span>,
        ]} indicator={boolean(true)} />)
    .add('Suggestion', () => 
        <SuggestionShort
            core={core}
        ></SuggestionShort>
    )
;
