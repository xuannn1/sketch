import * as React from 'react';
import { Link } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { BottomNavigationAction, withStyles, BottomNavigation } from '@material-ui/core';
import { HomeRounded, FavoriteRounded, PersonRounded, NotificationsRounded, WhatshotRounded } from '@material-ui/icons/index.es';

interface Props {
    core:Core;
    classes: {
        root: string;
    };
}

type LinkSpec = {link:string, label:string, icon?:JSX.Element};
interface State {
    value:number;
    spec:LinkSpec[];
}

class MyNavBar extends React.Component<Props, State> {
    public state = {
        value: 2,
        spec: [
            {link:ROUTE.home, label: 'home', icon: <HomeRounded />},
            {link:ROUTE.statuses,label: 'status', icon: <WhatshotRounded />},
            {link:ROUTE.collections, label: 'collection', icon: <FavoriteRounded />},
            {link:ROUTE.users,label: 'users', icon: <PersonRounded />},
            {link:ROUTE.notifications,label: 'notification', icon: <NotificationsRounded />},
        ],
    };

    public handleChange = (ev, value) => {
        this.setState({ value })
    }

    public renderLink = (spec:LinkSpec) => {
        const link = (props) => <Link to={spec.link} {...props} />;
        return <BottomNavigationAction 
            label={spec.label}
            icon={spec.icon}
            key={spec.label}
            component={link} 
        />;
    }

    public render = () => {
        const { classes } =  this.props;

        return <BottomNavigation
            value={this.state.value}
            className={classes.root}
            showLabels
            onChange={this.handleChange}>
            { this.state.spec.map((s) => this.renderLink(s)) }
        </BottomNavigation>
    }
}

export const Navbar_m = withStyles({
    root: {
        bottom: '0',
        position: 'fixed',
        left: '0',
        width: '100%',
        boxShadow: '0px -1px 2px rgba(0, 0, 0, 0.2)',
    },
})(MyNavBar)