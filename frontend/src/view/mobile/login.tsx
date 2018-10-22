import * as React from 'react';
import { Core } from '../../core';
import { Card, Page } from '../components/common';
import { Link } from 'react-router-dom';
import { ROUTE } from '../../config/route';

interface Props {
    core:Core;
}
interface State {
    email:string;
    password:string;
}

export class Login_m extends React.Component<Props, State> {
    public render () {
        return <Page>
            <Card style={{
                marginTop: '10vh',
            }}>
                <div className="card-header" style={{boxShadow: 'none'}}><h1 className="title">登录</h1></div>
                <div className="card-content">
                邮箱:
                <input className="input is-normal" type="email" onChange={(ev) => this.setState({email:ev.target.value})}></input>
                <br />
                密码:
                <input className="input is-normal" type="password" onChange={(ev) => this.setState({password:ev.target.value})}></input>
                <div style={{
                    marginTop: '1vh',
                    textAlign: 'justify',
                }}>
                    <label className="checkbox"><input type="checkbox" />记住我</label>
                    <a style={{
                        color: 'grey',
                        float: 'right',
                        fontSize: 'smaller',
                    }}>忘记密码/重新激活</a>
                </div>
                </div>
                <div className="card-footer">
                    <span>还没账号?&#160;&#160;</span><Link to={ROUTE.register}>现在注册</Link>!
                </div>
            </Card>
        </Page> 
    }
}