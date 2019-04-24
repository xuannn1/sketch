import * as React from 'react';
import { storiesOf, addDecorator } from '@storybook/react';
import { withViewport } from '@storybook/addon-viewport';
import { withConsole } from '@storybook/addon-console';
import { withKnobs } from '@storybook/addon-knobs';
import { Core } from '../src/core';
import '../src/theme.scss';
//import { Accordion } from '../src/view/components/common/accordion';
import { Badge } from '../src/view/components/common/badge';

// import { action } from '@storybook/addon-actions';

addDecorator((storyFn, context) => withConsole()(storyFn)(context));


let value:number = 10;
let max:number = 99;
storiesOf('Common Components', module)
  .addDecorator(withViewport())
  .addDecorator(withKnobs) 
  
  /* .add('Accordion', () => <Accordion 
    title={'test'}
    items={[]} 
  />) */

  // 在后面加入其他组件
  .add('Badge1', () => 
    <Badge num={value}>
      <span>
      test
      </span>
    </Badge>
  )
  .add('Badge2', () => 
    <Badge num={value+90} max={max}>
      <span>
      test
      </span>
    </Badge>
  )
  .add('Badge3', () => 
    <Badge dot>
      <span>
      test
      </span>
    </Badge>
  )
  .add('Badge4', () => 
    <Badge hidden>
      <span>
      test
      </span>
    </Badge>
  )
;

storiesOf('Home Components', module)
.addDecorator(withViewport())
.addDecorator(withKnobs)
;

storiesOf('User Components', module)
.addDecorator(withViewport())
.addDecorator(withKnobs)
;

storiesOf('Thread Components', module)
.addDecorator(withViewport())
.addDecorator(withKnobs)
;