import * as React from 'react';
import { Link } from 'react-router-dom';
import { Core } from '../../core';

interface Props {
    core:Core;
}

type LinkSpec = {to:string, label:string, icon?:string};

interface State {
    spec:LinkSpec[];
}

export class Navbar extends React.Component<Props, State> {
    public state = {
        spec: [
            {to:'/', label: 'home'},
            {to:'/status/all',label: 'status'},
            {to:'/collection/book', label: 'collection'},
            {to:'/user', label: 'user'},
            {to:'/message/all', label: 'message'},
        ],
    };

    public handleChange = (ev, value) => {
    }

    public renderLinks = (spec:LinkSpec, i:number) => {
        //todo: find icons here
        return <Link style={{
            flex: 1,
        }} 
            key={i}
            to={spec.to}
            className="navbar-item">
                {spec.label}
            </Link>;
    }

    public render = () => {
        return <nav style={{
            display: 'flex',
            textAlign: 'center',
            boxShadow: '0px -1px 2px rgba(0, 0, 0, 0.2)',
        }}
        className="navbar is-fixed-bottom">
            { this.state.spec.map(this.renderLinks)}
        </nav>;
    }
}