import * as React from 'react';
import { storiesOf, addDecorator } from '@storybook/react';
import { withViewport } from '@storybook/addon-viewport';
import { withConsole } from '@storybook/addon-console';
import { withKnobs } from '@storybook/addon-knobs';
import { Core } from '../src/core';
import '../src/theme.scss';
import { Accordion } from '../src/view/components/common/accordion';

// import { action } from '@storybook/addon-actions';

addDecorator((storyFn, context) => withConsole()(storyFn)(context));

storiesOf('Common Components', module)
  .addDecorator(withViewport())
  .addDecorator(withKnobs) 
  
  .add('Accordion', () => <Accordion 
    title={'test'}
    items={[]} 
  />)

  // 在后面加入其他组件
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