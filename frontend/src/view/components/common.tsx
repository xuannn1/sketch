import * as React from 'react';
import { parseDate } from '../../utils/date';
import { Link } from 'react-router-dom';
import { ResData } from '../../config/api';

export const COLOR_GREY = '#555';

export function Page (props:{
    children?:React.ReactNode,
    nav?:JSX.Element,
    className?:string,
}) {
    return <div className={props.className}>
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
    thread:ResData.Thread,
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
            }}>{props.thread.attributes.title}</a></div>

            <div style={{
                color: COLOR_GREY,
                opacity: 0.7,
                fontSize: '85%',
            }}>{props.thread.attributes.body}</div>
        </div> 

        { props.showDetail && props.thread.attributes.created_at && props.thread.attributes.edited_at && 
            <div style={{
                color: COLOR_GREY,
                opacity: 0.7,
                fontSize: '85%',
            }}>
                <span style={{marginRight: '5px'}}>{props.thread.author.attributes.name}</span>
                <span>{parseDate(props.thread.attributes.created_at)} / {parseDate(props.thread.attributes.edited_at)}</span>
            </div>
        }
    </div>
}