import * as ReactDOM from 'react-dom';
import React from 'react';
import { Main } from './view';
import { Handler } from './handlers';

(function () {
    const root = document.createElement('div');
    document.body.appendChild(root);

    const handler = new Handler();

    ReactDOM.render(
        <Main h={handler} />,
        root,
    );
})();