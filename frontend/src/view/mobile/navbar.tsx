import * as React from 'react';
import { Link } from 'react-router-dom';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';

interface Props {
    core:Core;
}

interface State {

}

export class Navbar_m extends React.Component<Props, State> {
    public render () {
        const spec = [
            {to:ROUTE.home, text:'home'},
            {to:ROUTE.statuses, text: 'status'},
            {to:ROUTE.collections, text: 'collection'},
            {to:ROUTE.users, text: 'users'},
            {to:ROUTE.notifications, text: 'notification'},
        ];
        return (<div className="navbar">
            { spec.map((s) => this.renderLink(s.to, s.text)) }
        </div>);
    }

    public renderLink (to:string, text:string) {
        return <div key={text}><Link to={to}>{ text }</Link></div>;
    }
}