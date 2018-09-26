import * as ReactDOM from 'react-dom';
import React from 'react';
import { Main } from './view';
import { Core } from './core';

(function () {
    const root = document.createElement('div');
    document.body.appendChild(root);

    const core = new Core();

    ReactDOM.render(
        <Main core={core} />,
        root,
    );
})();