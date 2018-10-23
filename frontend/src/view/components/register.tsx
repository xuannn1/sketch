import * as React from 'react';
import { Core } from '../../core';
import { Card, NotificationError } from './common';
import { validEmail, validPwd } from '../../utils/validates';

interface Props {
    core:Core;
}
interface State {
    username:string;
    email:string;
    pwd:string;
    pwd2:string;
    token:string;
    accept:boolean;
    errMsg:string;
}

export class Register extends React.Component<Props, State> {
    public state = {
        username: '',
        email: '',
        pwd: '',
        pwd2: '',
        token: '',
        accept: false,
        errMsg: '',
    };

    public inputStyle = 'input is-normal';

    public render () {
        return <Card>
            <div className="card-header" style={{boxShadow: 'none'}}>
                <h1 className="title">注册</h1>
            </div>
            <div className="card-content">
                {this.state.errMsg && <NotificationError>
                    { this.state.errMsg }
                </NotificationError>}

                用户名（笔名）：
                <input className={this.inputStyle}
                    type="text"
                    onChange={(ev) => this.setState({username: ev.target.value})}
                />

                邮箱：
                <input className={this.inputStyle}
                    type="email"
                    onChange={(ev) => this.setState({email: ev.target.value})}
                />
                
                密码：
                <input className={this.inputStyle}
                    type="password"
                    onChange={(ev) => this.setState({pwd: ev.target.value})}
                />
                
                确认密码：
                <input className={this.inputStyle}
                    type="password"
                    onChange={(ev) => this.setState({pwd2: ev.target.value})}
                />

                邀请码：
                <input className={this.inputStyle}
                    type="text"
                    onChange={(ev) => this.setState({token: ev.target.value})}
                />

                <div className="checkbox" style={{ textAlign: 'center', width: '100%' }}>
                    <input type="checkbox"
                        onChange={(ev) => this.setState({accept: ev.target.checked})}
                    />
                    我已阅读并同意注册协议 更多内容
                </div>

                <a className="button is-normal is-fullwidth" onClick={(ev) => {
                    if (this.state.email === '') {
                        this.setState({errMsg: '邮箱 不能为空。'});
                    } else if (this.state.pwd === '') {
                        this.setState({errMsg: '密码 不能为空。'});
                    } else if (this.state.username === '') {
                        this.setState({errMsg: '名称 不能为空。'});
                    } else if (this.state.token === '') {
                        this.setState({errMsg: '邀请码 不能为空。'});
                    } else if (!this.state.accept) {
                        this.setState({errMsg: '注册协议勾选 不能为空。'});
                    } else if (!validEmail(this.state.email)) {
                        this.setState({errMsg: '邮箱格式不符'});
                    } else if (!validPwd(this.state.pwd)) {
                        this.setState({errMsg: '密码格式不符'});
                    } else if (this.state.pwd !== this.state.pwd2) {
                        this.setState({errMsg: '密码和确认密码不相匹配'});
                    } else {

                    }
                }}>注册</a>
            </div>
        </Card>;
    }
}