import * as React from 'react';
import { storiesOf } from '@storybook/react';
import { Carousel } from '../src/view/components/carousel';
import { CardDecorator } from './decorator';
import { withViewport } from '@storybook/addon-viewport';

// import { action } from '@storybook/addon-actions';

storiesOf('Carousel', module)
    .addDecorator(withViewport())
    .add('text', () => 
        <Carousel slides={[
            <span>one</span>,
            <span>two</span>,
            <span>three</span>,
        ]} />)
    .add('text indicator', () => 
        <Carousel slides={[
            <span>one</span>,
            <span>two</span>,
            <span>three</span>, 
        ]} indicator={true} />
    );