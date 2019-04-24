import * as React from 'react';
import { storiesOf, addDecorator } from '@storybook/react';
import { Carousel } from '../src/view/components/common/carousel';
import { Badge } from '../src/view/components/common/badge';
import { withViewport } from '@storybook/addon-viewport';
import { withConsole } from '@storybook/addon-console';
import { withKnobs, boolean } from '@storybook/addon-knobs';
import { Core } from '../src/core';
// import { Chapter } from '../src/view/components/book/chapter';
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
;

let value:number = 10;
let max:number = 99;
storiesOf('Home', module)
  .addDecorator(withViewport())
  .addDecorator(withKnobs)
  .addDecorator(pageDecorator)
  .add('Badge1', () => 
    <Badge num={value}>
      <span>
      test
      </span>
    </Badge>
  )
;

storiesOf('Home', module)
  .addDecorator(withViewport())
  .addDecorator(withKnobs)
  .addDecorator(pageDecorator)
  .add('Badge2', () => 
    <Badge num={value+90} max={max}>
      <span>
      test
      </span>
    </Badge>
  )
;


storiesOf('Home', module)
  .addDecorator(withViewport())
  .addDecorator(withKnobs)
  .addDecorator(pageDecorator)
  .add('Badge3', () => 
    <Badge dot>
      <span>
      test
      </span>
    </Badge>
  )
;

storiesOf('Home', module)
  .addDecorator(withViewport())
  .addDecorator(withKnobs)
  .addDecorator(pageDecorator)
  .add('Badge4', () => 
    <Badge hidden>
      <span>
      test
      </span>
    </Badge>
  )
;
// storiesOf('Book', module)
//   .addDecorator(withViewport())
//   .addDecorator(withKnobs)
//   .add('Chapter', () =>
//     <Chapter chapter={{
//       type: 'chapter',
//       id: 0,
//       attributes: {
//       title: '',
//       body: '',
//       }
//     }} /> 
//   )
// ;
