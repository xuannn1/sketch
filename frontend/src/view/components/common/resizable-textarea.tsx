import * as React from 'react';
import './resizable-textarea.scss';
import { classnames } from '../../../utils/classname';

export class ResizableTextarea extends React.PureComponent<{
  // props
  minRows:number;
  maxRows:number; //default 3
  placeholder:string
  style?:React.CSSProperties;
  className?:string;
}, {
  // state
  value:string;
  rows:number;
}> {

  public static defaultProps = {
    minRows: 1,
    maxRows: 3,
    placeholder:'',
  };

  constructor(props){
    super(props);
    this.state = {
      value:'',
      rows: props.minRows,
    };
  }

  private handleChange = (event) => {
    const textareaLineHeight = 24;
    const { minRows, maxRows } = this.props;
    console.log(event.target.scrollHeight, event.target.scrollTop, event.target.rows);
    const previousRows = event.target.rows;
    event.target.rows = minRows; // reset number of rows in textarea
    const currentRows = ~~(event.target.scrollHeight / textareaLineHeight); //~~: similar to Math.floor(), it basically just remove the right part of the decimal point

    if (currentRows === previousRows) {
      event.target.rows = currentRows;
    }

    if (currentRows >= maxRows) {
      event.target.rows = maxRows;
      event.target.scrollTop = event.target.scrollHeight;
    }

    this.setState({
      value: event.target.value,
      rows: currentRows < maxRows ? currentRows : maxRows,
    });
  }

  public render () {
    return (
      <div className={'text-box-container'}>
        <textarea
          rows={this.state.rows}
          value={this.state.value}
          placeholder={this.props.placeholder}
          className={'textarea text-box'}
          onChange={this.handleChange}/>
    </div>
    );
  }
}
