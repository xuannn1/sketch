import * as React from 'react';
import { Redirect, Link  } from 'react-router-dom';

export class SearchHomepageBar extends React.Component<{
  hasInfo:boolean;
  onSearch:() => void;
  onInfo:() => void;
  style?:Object
}, {}> {

  public render () {
    return <div style={Object.assign({
      display:'flex',
      flexDirection: 'row',
      justifyContent: 'center',
      alignItems:'center',
      margin:'0',
      padding:'0',
      width:'100%',
    } , this.props.style || {})}>
    <div style={{
      margin: '5px auto',
      backgroundColor: 'white',
      padding: '5px 0',
      borderRadius: '15px',
      textAlign: 'center',
      width: '85%',
      color: 'rgba(190,190,190,1)',
      fontSize: '1em',
    }} onClick={this.props.onSearch}>
      <i className="fa fa-search i00" id="i-advanced-search-i"></i>&nbsp;搜索文章、作者
    </div>
    <div style={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        margin: 'auto',
        color: '#f76a24',
        }} onClick={this.props.onInfo}>
      <i className="fas fa-bullhorn" style={{color: this.props.hasInfo ? 'red' : 'black'}}></i>
      <span>消息</span>
    </div>
    </div>;
  }
}




