import { configure } from '@storybook/react';
import { configureViewport, INITIAL_VIEWPORTS } from '@storybook/addon-viewport';


function loadStories() {
  // automatically import all files ending in *.stories.ts
  // require.context('../stories', true, /.stories.tsx$/);
  require('../stories/index.stories.tsx');
}

configure(loadStories, module);
configureViewport({
  defaultViewport: 'iphone8p'
});