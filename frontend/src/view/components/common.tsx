import * as React from 'react';

export function Page (props) {
    return <div style={{
        margin: '5px 10px',
    }}>{props.children}</div>
}

export function Card (props) {
    return <div className="card" style={{
        marginTop: '10px',
        padding: '5px',
    }}>{props.children}</div>
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