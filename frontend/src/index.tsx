import * as ReactDOM from 'react-dom';
import * as React from 'react';
import { App } from './view';
import { Core } from './core';
import './theme.scss';

(async function () {
    const root = document.createElement('div');
    document.body.appendChild(root);

    const core = new Core();

    ReactDOM.render(
        <App core={core} />,
        root,
    );
})();