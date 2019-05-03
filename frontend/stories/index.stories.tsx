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
import { FilterBar } from '../src/view/components/common/filter-bar';
import { Dropdown } from '../src/view/components/common/dropdown';
import { Popup } from '../src/view/components/common/popup';
import { Center } from '../src/view/components/common/center';

import '@fortawesome/fontawesome-free-webfonts/css/fontawesome.css';
import '@fortawesome/fontawesome-free-webfonts/css/fa-regular.css';
import '@fortawesome/fontawesome-free-webfonts/css/fa-solid.css';
import '@fortawesome/fontawesome-free-webfonts/css/fa-brands.css';

addDecorator((storyFn, context) => withConsole()(storyFn)(context));
addDecorator(withViewport());
addDecorator(withKnobs);

storiesOf('Common Components', module)
  .add('Badge', () => <Badge num={number('num', 10)}
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
  .add('TagList', () => {
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
        onClick={action('tag click ' + i)}
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
  .add('Dropwdown', () => <Dropdown
    list={[{text: '1', value: 1}, {text: '2', value: 2}]}
    onIndex={0}
    onClick={action('onClick')}
  />)
  .add('Dropdown (with title)', () => {
    return <Dropdown
      list={[{text: '1', value: 1}, {text: '2', value: 2}]}
      title={text('title', 'dropdown menu')}
      onClick={action('onClick')}
    />;
  })
  .add('FilterBar', () => {
    return <FilterBar></FilterBar>
  })
  .add('Popup', () => React.createElement(class extends React.Component {
    public state = {
      showPopup: true,
      darkerBackgrond: boolean('darkerBackground', true),
      content: text('content', 'test'),
      bottom: boolean('bottom', false),
    };
    public render () {
      return <div>
        <button className="button" onClick={() => this.setState({showPopup: true})}>show popup</button>
        {this.state.showPopup &&
          <Popup
            bottom={this.state.bottom}
            darkerBackground={this.state.darkerBackgrond}
            onClose={() => this.setState({showPopup: false})}>
            <div>{this.state.content}</div>
          </Popup>
        }
      </div>;
    }
  }))
  .add('Center', () => <Center width={text('width', '')} height={text('height','')}>
    <div>center anything</div>
  </Center>)
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