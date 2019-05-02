import * as React from 'react';
import { storiesOf, addDecorator } from '@storybook/react';
import { withViewport } from '@storybook/addon-viewport';
import { withConsole } from '@storybook/addon-console';
import { withKnobs, text, boolean, number, select } from '@storybook/addon-knobs';
import '../src/theme.scss';
import { Badge } from '../src/view/components/common/badge';
import { action } from '@storybook/addon-actions';
import { Tag } from '../src/view/components/common/tag';
import { TagList } from '../src/view/components/common/tag-list';

addDecorator((storyFn, context) => withConsole()(storyFn)(context));
addDecorator(withViewport());
addDecorator(withKnobs);

storiesOf('Common Components', module)
  .add('Badge', () => 
    <Badge num={number('num', 10)}
      max={number('max', 0)}
      dot={boolean('dot', false)}
      hidden={boolean('hidden', false)}>
      {text('text', 'test')}
    </Badge>
  )
  .add('Tag', () => {
    const colorOptions = {
      default: '',
      black: 'black',
      dark: 'dark',
      light: 'light',
      white: 'white',
      primary: 'primary',
      link: 'link',
      info: 'info',
      success: 'success',
      warning: 'warning',
      danger: 'danger',
    };
    return <Tag
      selected={boolean('selected', false)}
      onClick={action('tag click')}
      size={select('size', {
        normal: 'normal',
        medium: 'medium',
        large: 'large',
      }, 'normal')}
      color={select('color', colorOptions, '')}
      selectedColor={select('selectedColor', colorOptions)}
      rounded={boolean('rounded', false)}
      selectable={boolean('selectable', true)}
    >{text('text', 'test')}</Tag>;
  })
  .add('Tag List', () => {
    const colorOptions = {
      default: '',
      black: 'black',
      dark: 'dark',
      light: 'light',
      white: 'white',
      primary: 'primary',
      link: 'link',
      info: 'info',
      success: 'success',
      warning: 'warning',
      danger: 'danger',
    };
    return <TagList>
      {(new Array(number('length', 20)).fill(text('text', 'tag')).map((text, i) => <Tag
        key={i} 
        onClick={action('tag click', i)}
        size={select('size', {
          normal: 'normal',
          medium: 'medium',
          large: 'large',
        }, 'normal')}
        color={select('color', colorOptions, '')}
        selectedColor={select('selectedColor', colorOptions)}
        rounded={boolean('rounded', false)}
        selectable={boolean('selectable', true)}
      >{text}</Tag>))}
    </TagList>
  })
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