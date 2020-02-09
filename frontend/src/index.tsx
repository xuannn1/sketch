import * as ReactDOM from 'react-dom';
import * as React from 'react';
import { App } from './view';
import { Core } from './core';

import '@fortawesome/fontawesome-free-webfonts/css/fontawesome.css';
import '@fortawesome/fontawesome-free-webfonts/css/fa-regular.css';
import '@fortawesome/fontawesome-free-webfonts/css/fa-solid.css';
import '@fortawesome/fontawesome-free-webfonts/css/fa-brands.css';
import './theme.scss';

import { fakeDB } from './test/mock-data/fake-db';

async function run () {
  const core = new Core();
  // fakeDB(core.db);

  const root = document.createElement('div');
  root.id = 'app';
  document.body.appendChild(root);

  ReactDOM.render(
    <App core={core} />,
    root,
  );
}

run().catch(console.error);