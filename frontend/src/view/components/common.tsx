import * as React from 'react';

export function Page (props) {
    return <div style={{
        margin: '5px 10px',
    }}>{props.children}</div>
}

export function Card (props:{
    children?:React.ReactNode,
    style?:React.CSSProperties,
}) {
    return <div className="card" style={Object.assign({
        marginTop: '10px',
        padding: '5px',
    }, props.style || {})}>{props.children}</div>
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
        <button className="delete">{props.children}</button>
    </div>;
}