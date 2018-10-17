import * as React from 'react';
import { Link } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import { MyBottomNavigation } from '../components/common';
import BottomNavigationAction from '@material-ui/core/BottomNavigationAction';

interface Props {
    core:Core;
}

type LinkSpec = {link:string, label:string, icon?:JSX.Element};
interface State {
    value:number;
    spec:LinkSpec[];
}

export class Navbar_m extends React.Component<Props, State> {
    public state = {
        value: 2,
        spec: [
            {link:ROUTE.home, label: 'home'},
            {link:ROUTE.statuses,label: 'status'},
            {link:ROUTE.collections, label: 'collection'},
            {link:ROUTE.users,label: 'users'},
            {link:ROUTE.notifications,label: 'notification'},
        ],
    };

    public handleChange = (ev, value) => {
        this.setState({ value })
    } 

    public render () {
        const { classes } = this.props as any;

        return <MyBottomNavigation
            value={this.state.value}
            showLabels
            className={classes.root}
            onChange={this.handleChange}>
            { this.state.spec.map((s) => this.renderLink(s)) }
        </MyBottomNavigation>
    }

    public renderLink (spec:LinkSpec) {
        return <Link to={spec.link}>
            <BottomNavigationAction label={spec.label} icon={spec.icon} />
        </Link>;
    }
}