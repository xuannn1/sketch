import * as React from 'react';
import { Link } from 'react-router-dom';

export enum MenuPosition {
  default,
  top,
  bottom,
}

interface Props {
  position?:MenuPosition;
  icon?:boolean;
  items:{to:string, label:string, icon?:string}[];
  style?:React.CSSProperties;
  onIndex?:number;
  onStyle?:React.CSSProperties;
}
interface State {
}

export class Menu extends React.Component<Props, State> {
  public PositionStyle:{[pos in MenuPosition]:React.CSSProperties} = {
    [MenuPosition.default]: {},
    [MenuPosition.top]: {},
    [MenuPosition.bottom]: {},
  }
  public render () {
    const positionStyle = this.props.position ? this.PositionStyle[this.props.position] : {};
    const onStyle = Object.assign({
      textDecoration: 'underline',
    }, this.props.onStyle || {});

    const linkStyle:React.CSSProperties = {
      flex: 1,
      color: 'black',
    };

    return <div style={Object.assign({
      display: 'flex',
      textAlign: 'center',
      justifyContent: 'center',
      alignItems: 'center',
      position: 'relative',
      height: '100%',
    }, positionStyle, this.props.style || {})}>
      {this.props.items.map((item, i) => {
        return <Link
          key={i}
          to={item.to}
          style={this.props.onIndex !== undefined ? (
          i === this.props.onIndex ? 
            Object.assign({}, linkStyle, onStyle) :
            linkStyle
          ) : linkStyle}
        >{item.label}</Link>
      })}
    </div>;
  }
}