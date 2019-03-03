import * as React from 'react';
import { Core } from '../../core/index';
import { Anchor } from './common';

interface Props {
    core:Core;
    right?:React.ReactNode;
    center:React.ReactNode|string;
}

interface State {}

export class Topnav extends React.Component<Props, State> {
    public render () {
        return <div style={{
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            textAlign: 'center',
            top: 0,
            left: 0,
            width: '100%',
            minHeight: '2.25rem',
            backgroundColor: 'white',
        }} className="navbar" role="navigation" aria-label="main navigation">
            <div className="navbar-brand" style={{
                position: 'absolute',
                left: 0,
                zIndex: 10,
            }}>
                <a className="navbar-item prev" onClick={this.goBack}>&#10094;</a> 
            </div>

            <div className="navbar-start">
                {this.renderCenterElements()}
            </div>
            
            {this.props.right &&
                <div className="navbar-end" style={{position: 'absolute', right: 0}}>
                    <div className="navbar-item">{this.props.right}</div>
                </div>
            }
        </div>;
    }

    public goBack = (ev:React.MouseEvent<HTMLAnchorElement>) => {
        this.props.core.history.goBack();
    }

    public renderCenterElements () : JSX.Element|JSX.Element[] {
        const { center } = this.props;
        if (typeof center === 'string') {
            return <span className="navbar-item">{center}</span>
        } else {
            return <div className="navbar-item">{center}</div>
        }
    }
}