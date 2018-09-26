import * as React from 'react';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';

interface Props {
    core:Core;
}

interface State {

}

export class Footer extends React.Component<Props, State> {
    public render () {
        return (<div className="container">
            <div className="col-md-10 col-md-offset-1">
                <footer className="footer">
                    <span className="slogan">
                        <img className="brand-icon" src="/img/So-logo.ico" alt="sosad-logo" />
                        <a href={ROUTE.home}>首页</a>
                        <a href={ROUTE.contacts}>联系我们</a>
                        <ul>
                            <li><a href={ROUTE.adminstrationRecords}>管理记录</a></li>
                            <li><a href={ROUTE.github}><i className="fa fa-github" aria-hidden="true"></i></a></li>
                            <li><a href={ROUTE.about}>关于</a></li>
                            <li><a href={ROUTE.help}>帮助</a></li>
                        </ul>
                    </span>
                </footer>
            </div>
        </div>);
    }
}