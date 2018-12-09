import * as React from 'react';
import { DataType } from '../../config/data-types';
import { parseDate } from '../../utils/date';
import { ROUTE } from '../../config/route';
import { Link } from 'react-router-dom';

export const COLOR_GREY = '#555';

export function Page (props:{
    children?:React.ReactNode,
    nav?:JSX.Element,
}) {
    return <div>
        {props.nav}
        <div style={{
            margin: '5px 10px',
        }}>{props.children}</div>
    </div>; 
    

}

export function Card (props:{
    children?:React.ReactNode,
    style?:React.CSSProperties,
    className?:string;
    ref?:(el:HTMLDivElement|null) => void;
}) {
    return <div className={`card ${props.className || ''}`}
        ref={(el) => props.ref && props.ref(el)}
        style={Object.assign({
            marginTop: '10px',
            padding: '10px',
            position: 'relative',
        }, props.style || {})}>

        {props.children}

    </div>;
}

export function TopMenu (props:{
    items:{to:string, label:string}[],
}) {
    return <nav className="navbar" style={{
        display: 'flex',
        padding: '0 30vw',
    }}>
        {props.items.map((item, i) => {
            return <Link className="navbar-item"
                key={i}
                to={item.to}
                style={{
                    margin: 'auto',
                    textDecoration: 'underline',
                }}
            >{item.label}</Link>
        })}
    </nav>;
}

export function NotificationError (props:{
    children:React.ReactNode,
}) {
    return <div className="notification is-danger">
        <button className="delete"></button>
        {props.children}
    </div>;
}

export function ShortThread (props:{
    thread:DataType.Home.Thread,
    link:string,
    showDetail?:boolean,
    style?:React.CSSProperties,
}) {
    return <div style={Object.assign({}, props.style || {})}>
            <div>
            <div><a href={props.link} style={{
                display: 'inline-block',
                fontWeight: 700,
                lineHeight: 2,
                color: COLOR_GREY,
                textDecoration: 'none',
            }}>{props.thread.title}</a></div>

            <div style={{
                color: COLOR_GREY,
                opacity: 0.7,
                fontSize: '85%',
            }}>{props.thread.content}</div>
        </div> 

        { props.showDetail && props.thread.create_date && props.thread.update_date && 
            <div style={{
                color: COLOR_GREY,
                opacity: 0.7,
                fontSize: '85%',
            }}>
                <span style={{marginRight: '5px'}}>{props.thread.username}</span>
                <span>{parseDate(props.thread.create_date)} / {parseDate(props.thread.update_date)}</span>
            </div>
        }
    </div>
}