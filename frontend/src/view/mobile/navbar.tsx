import * as React from 'react';
import * as classNames from 'classnames';
import { Link } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { BottomNavigationAction, withStyles, BottomNavigation } from '@material-ui/core';
import { HomeRounded, FavoriteRounded, PersonRounded, NotificationsRounded, WhatshotRounded } from '@material-ui/icons/index.es';

interface Props {
    core:Core;
    classes: { root: string; };
}

type LinkSpec = {to:string, label:string, icon?:JSX.Element};
interface State {
    value:number;
    spec:LinkSpec[];
}

const _BottomNavigationAction = withStyles({
    root: {
        minWidth: '0'
    }
})(
    class extends React.Component<{
        link:LinkSpec;
        classes:{root:string};
    }, {}> {
        public link = (props) => <Link to={this.props.link.to} {...props} />
        public render () {
            const { link, classes, ...others } = this.props;
            return <BottomNavigationAction
                label={link.label}
                icon={link.icon}
                component={this.link}
                className={classes.root}
                {...others}
            />;
        }
    }
);

export class MyNavBar extends React.Component<Props, State> {
    public state = {
        value: 2,
        spec: [
            {to:ROUTE.home, label: 'home', icon: <HomeRounded />},
            {to:ROUTE.statuses,label: 'status', icon: <WhatshotRounded />},
            {to:ROUTE.collections, label: 'collection', icon: <FavoriteRounded />},
            {to:ROUTE.users,label: 'users', icon: <PersonRounded />},
            {to:ROUTE.notifications,label: 'notification', icon: <NotificationsRounded />},
        ],
    };

    public handleChange = (ev, value) => {
        this.setState({ value })
    }

    public render = () => {
        return <BottomNavigation
            value={this.state.value}
            className={this.props.classes.root}
            showLabels
            onChange={this.handleChange}>
            { this.state.spec.map((link, i) => <_BottomNavigationAction link={link} key={i} />) }
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