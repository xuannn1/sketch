import * as React from 'react';
import { Core } from '../../core/index';

interface Props {
    core:Core;
    text?:string;
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
        }}>
            <div style={{
                position: 'absolute',
                left: 0,
                zIndex: 10,
            }}>
                <a className="navbar-item prev" onClick={this.goBack}>&#10094;</a> 
            </div>

            <div style={{
                flex: 1,
            }}>
                <a className="navbar-item">
                    { this.props.text }
                </a>
            </div>
        </div>;
    }

    public goBack = (ev:React.MouseEvent<HTMLAnchorElement>) => {
        this.props.core.history.goBack();
    }
}