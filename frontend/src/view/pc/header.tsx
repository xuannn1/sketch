import * as React from 'react';
import { Core } from '../../core';
import { ROUTE } from '../../config/route';
import './header.scss';

interface Props {
    core:Core;
}

interface State {

}

export class Navbar extends React.Component<Props, State> {
    public render () {
        const { core } = this.props;
        return (<div className="navbar">
            <div className="container">
                <div className="col-md-offset-1 col-md-10">
                    <a href={ROUTE.home}>废文网</a>
                    <input type="hidden" name="baseurl" value={ROUTE.home} />
                    <nav>
                        <ul className="nav navbar-nav navbar-right text-right">
                            { core.user.isAdmin() && <li><a href={ROUTE.admin} className="admin-symbol">管理员</a></li> }
                            { !core.user.hasSigned() && <li><a href={ROUTE.qiandao} style={{ color: '#d66666' }}>我要签到</a></li> }
                            <li><a href={ROUTE.statuses}>动态</a></li>
                            <li><a href={ROUTE.books}>文库</a></li>
                            <li><a href={ROUTE.threads}>论坛</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>);
    }
}