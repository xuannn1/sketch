import * as React from 'react';
import { Card, withStyles } from '@material-ui/core';

export const MyCard = withStyles({
    root: {
        minHeight: '15vw',
        padding: '5px',
        marginTop: '8px',
    },
})(Card);