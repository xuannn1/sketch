import * as React from 'react';
import './resizable-textarea.scss';
import { classnames } from '../../../utils/classname';

export class ResizableTextarea extends React.PureComponent<{
  // props
  minRows:number;
  maxRows:number; //default 3
  placeholder:string;
  value:string;
  onChange:(value:string) => void;
  style?:React.CSSProperties;
  className?:string;
}, {
  // state
  rows:number;
}> {

  public static defaultProps = {
    minRows: 1,
    maxRows: 3,
    placeholder:'',
  };

  constructor(props) {
    super(props);
    this.state = {
      rows: props.minRows,
    };
  }

  public componentWillUpdate (nextProps) {
    // shrink text box after clear the content
    if (nextProps.value != this.props.value && nextProps.value == '') {
      this.setState({
        rows: this.props.minRows,
      });
    }
  }

  private handleChange = (event) => {
    const textareaLineHeight = 24;
    const { minRows, maxRows, onChange } = this.props;
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
      rows: currentRows < maxRows ? currentRows : maxRows,
    });
    onChange(event.target.value);
  }

  public render () {
    return (
      <div className="text-box-container" style={this.props.style}>
        <textarea
          rows={this.state.rows}
          value={this.props.value}
          placeholder={this.props.placeholder}
          className="textarea text-box"
          onChange={this.handleChange}/>
    </div>
    );
  }
}
