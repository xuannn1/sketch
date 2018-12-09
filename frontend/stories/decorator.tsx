import * as React from 'react';

export const CardDecorator = (story) => <div style={{
    boxShadow: '1px 0px 1px 0px rgba(0, 0, 0, 0.3);',
}}>{story()}</div>;
