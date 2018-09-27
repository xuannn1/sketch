import * as React from 'react';
import { Core } from '../../core';

interface Props {
    core:Core;
}

interface State {
    tongrenDisplay:string;
    searchPlaceHolder:string;
    selectValue:string;
    searchValue:string;
    tongrenValue:string;
}

export class Search extends React.Component<Props, State> {
    public defaultState = {
        tongrenDisplay: 'none',
        searchPlaceHolder: '搜索...',
        selectValue: '',
        searchValue: '',
        tongrenValue: '',
    }

    public state = Object.assign(this.defaultState);

    public render () {
        return (<div className="container-fluid">
            <div className="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <div className="search-container">
                    <select name="search_options" onChange={this.handleSelectChange} value={this.state.selectValue}>
                        <option value="threads">标题</option>
                        <option value="users">用户</option>
                        <option value="tongren_yuanzhu">同人</option>
                    </select>
                    <input type="textarea" 
                        placeholder={this.state.searchPlaceHolder}
                        style={{ maxWidth: '30%' }} 
                        onChange={(e) => this.setState({tongrenValue: e.target.value})}
                        value={this.state.searchValue} />
                    <input type="textarea"
                        placeholder="同人"
                        onChange={(e) => this.setState({tongrenValue: e.target.value})}
                        value={this.state.tongrenValue}
                        style={{ maxWidth: '30%', display: this.state.tongrenDisplay }} />
                    <button onClick={this.handleSubmit}><i className="fa fa-search"></i></button> 
                </div>
            </div>
        </div>);
    }

    public handleSelectChange = (e:React.ChangeEvent<HTMLSelectElement>) => {
        if (e.target.value === 'tongren_yuanzhu') {
            this.setState({
                tongrenDisplay: 'inline',
                searchPlaceHolder: '同人原著',
                selectValue: e.target.value,
            });
        } else {
            this.setState({
                tongrenDisplay: this.defaultState.tongrenDisplay,
                searchPlaceHolder: this.defaultState.searchPlaceHolder,
                selectValue: e.target.value,
            });
        }
    }

    public handleSubmit = (e:React.MouseEvent<HTMLButtonElement>) => {
        const { selectValue, searchValue, tongrenValue } = this.state;
        this.props.core.db.search(selectValue, searchValue, tongrenValue);
        this.setState(this.defaultState);
    }
}