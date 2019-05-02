import * as React from 'react';

type TagColor = 'black'|'dark'|'light'|'white'|'primary'|'link'|'info'|'success'|'warning'|'danger'; 

export class Tag extends React.Component<{
  // props
  children?:React.ReactNode;
  className?:string;
  style?:React.CSSProperties;
  onClick?:(selected:boolean) => void;
  selected?:boolean;
  size?:'normal'|'medium'|'large';
  color?:TagColor;
  selectedColor?:TagColor;
  rounded?:boolean;
  selectable?:boolean;
}, {
  // states
  selected:boolean;
}> {
  public state = {
    selected: this.props.selected || false,
  }

  public render () {
    const selectedColor = this.props.selectedColor ? 'is-' + this.props.selectedColor : 'is-warning';
    const color = this.props.color ? 'is-' + this.props.color : '';
    const size = this.props.size ? 'is-' + this.props.size : '';
    const className = this.props.className || '';
    const rounded = this.props.rounded ? 'is-rounded' : '';

    return <a className={`tag ${className} ${this.props.selectable ? (this.state.selected ? selectedColor : color) : selectedColor} ${size} ${rounded}`}
      style={this.props.style}
      onClick={() => {
        if (!this.props.selectable) { return; }
        this.setState((prevState) => {
          this.props.onClick && this.props.onClick(!prevState.selected);
          return {
            selected: !prevState.selected,
          };
        });
      }}>{this.props.children}</a>;
  }
}