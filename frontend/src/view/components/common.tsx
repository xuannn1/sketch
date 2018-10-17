import { withStyles, Card, Paper, BottomNavigation } from '@material-ui/core';

export const MyCard = withStyles({
    root: {
        minHeight: '15vw',
        padding: '5px',
        marginTop: '8px',
    },
})(Card);

export const MyBottomNavigation = withStyles({
    root: {
        bottom: '0',
        position: 'fixed',
        left: '0',
        width: '100%',
        boxShadow: '0px -1px 2px rgba(0, 0, 0, 0.2)',
    },
})(BottomNavigation);