import * as React from 'react';
import { parseDate } from '../../utils/date';
import { Link } from 'react-router-dom';
import { ResData } from '../../config/api';
import { URLParser } from '../../utils/url';
import './styles/common.scss';

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

export class Pagination extends React.Component <{
    style?:React.CSSProperties,
    className?:string,
    currentPage:number,
    lastPage:number, 
}, {}> {
    public FirstShowPages = 3;
    public MiddleShowPages = 10;
    public LastShowPages = 3;

    public render () {
        const pages = new Array(this.props.lastPage);
        pages.fill(0);
    
        return <div className={this.props.className}
            style={Object.assign({}, this.props.style || {})}>
            <nav className="pagination is-centered is-small" role="navigation" aria-label="pagination">
                <Anchor className="pagination-previous"
                    to={(new URLParser()).setQuery('page', this.props.currentPage - 1).getPathname()}
                    isDisabled={this.props.currentPage === 1}>&lt;</Anchor>
                <Anchor className="pagination-next"
                    to={(new URLParser()).setQuery('page', this.props.currentPage + 1).getPathname()}
                    isDisabled={this.props.currentPage === this.props.lastPage}>&gt;</Anchor>
                <ul className="pagination-list">
                { pages.map((_, idx) => {
                    const page = idx + 1;
                    if (page < this.FirstShowPages ||
                        page > this.props.lastPage - this.LastShowPages ||
                        page < this.props.currentPage + this.MiddleShowPages / 2 ||
                        page > this.props.currentPage - this.MiddleShowPages / 2 ) {
                        return <li key={page}>
                            <Link
                                className={`pagination-link ${page === this.props.currentPage && 'is-current'}`}
                                to={(new URLParser()).setQuery('page', page).getPathname()}>{page}</Link>
                        </li>;
                    }
                    if ((page === this.FirstShowPages + 1 && this.props.currentPage - this.MiddleShowPages / 2 > this.FirstShowPages + 1) ||
                        (page === this.props.lastPage - 1 - this.LastShowPages && this.props.currentPage + this.MiddleShowPages / 2 < this.props.lastPage - 1 - this.LastShowPages)) {
                        return <li key={page}>
                            <span className="pagination-ellipsis">&hellip;</span>
                        </li>;
                    }
                    return null;
                })}
                </ul>
            </nav>
        </div>;
    }
}

export class Anchor extends React.Component <{
    className?:string;
    isDisabled?:boolean;
    to:string;
    children?:React.ReactNode;
    role?:string;
}, {}> {
    public el:HTMLAnchorElement|null = null;
    public componentDidUpdate () {
        if (!this.el) { return; }
        if (this.props.isDisabled) {
            this.el.setAttribute('disabled', '');
        } else {
            this.el.removeAttribute('disabled');
        }
    }
    public render () {
        return <Link
            className={this.props.className}
            to={this.props.to}
            role={this.props.role}
            innerRef={(el) => this.el = el}>{this.props.children}</Link>;
    }
}

export class TabCard extends React.Component<{
    tabs:{name:string, children:React.ReactNode}[];
    className?:string;
    more?:string;
}, {
    onTab:number;
}> {
    public state = {
        onTab: 0,
    }

    public render () {
        const className = this.props.className || '';
        return <Card className={`tab-card ${className}`}>
            <div className="tabs is-boxed">
                <ul>
                    {this.props.tabs.map((tab, i) =>
                        <li key={i}
                            onClick={() => this.setState({onTab: i})}
                            className={this.state.onTab === i ? 'is-active' : ''}>
                            <a><span>{tab.name}</span></a>
                        </li>)}
                </ul>
            </div>
            <div className="tab-content">
                {this.props.tabs[this.state.onTab].children}
            </div>
            { this.props.more &&
                <Link className="more" to={this.props.more}>
                    更多
                </Link>
            }
        </Card>;
    }
}