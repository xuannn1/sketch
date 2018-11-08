import * as React from 'react';

export const COLOR_GREY = '#555';

export function Page (props) {
    return <div style={{
        margin: '5px 10px',
    }}>{props.children}</div>
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
            padding: '5px',
            position: 'relative',
        }, props.style || {})}>

        {props.children}

    </div>;
}

export function NavTop<NavType> (props:{
    items:{to:NavType, label:string, onClick:(nav:NavType) => void}[],
}) {
    return <nav className="navbar" style={{
        display: 'flex',
        padding: '0 30vw',
    }}>
        {props.items.map((item, i) => {
            return <div className="navbar-item"
                key={i}
                onClick={() => item.onClick(item.to)}
                style={{
                    margin: 'auto',
                    textDecoration: 'underline',
                }}
            >{item.label}</div>
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
    title:string;
    content:string;
    link:string;
    username?:string;
    createDate?:string;
    updateDate?:string;
    style?:React.CSSProperties;
}) {
    return <div style={Object.assign({}, props.style || {})}>
        <div><a href={props.link} style={{
            display: 'inline-block',
            fontWeight: 700,
            lineHeight: 2,
            color: COLOR_GREY,
            textDecoration: 'none',
        }}>{props.title}</a></div>

        <div style={{
            color: COLOR_GREY,
            opacity: 0.7,
            fontSize: '85%',
        }}>{props.content}</div>
    </div>
}