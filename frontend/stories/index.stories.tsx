import * as React from 'react';
import { storiesOf } from '@storybook/react';
import { Carousel } from '../src/view/components/carousel';
// import { action } from '@storybook/addon-actions';

storiesOf('Carousel', module)
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