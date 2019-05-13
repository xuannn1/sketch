import * as React from 'react';

interface Props {
  length:number;
  mark?:number;
  onClick?:(mark:number) => void;
}
interface State {
  currentValue:number, 
  value:number,
}

export class Mark extends React.Component<Props, State> {

  constructor(props:Props) {
    super(props);

    this.state = {
      currentValue: this.props.mark ? this.props.mark - 1 : -1,
      value: -1,
    }
  }

  selectValue(value:number) {
    const {onClick, mark} = this.props;
    if(mark) {
      return;
    }

    this.setState({
      currentValue: value,
      value,
    }, () => {
      onClick && onClick(value + 1)
    });
  }

  setCurrentValue(e:Object, value:number) {

    const {mark} = this.props;
    if(mark) {
      return;
    }

    this.setState({
      currentValue: value
    });
  }

  resetCurrentValue() {

    const {mark} = this.props;
    if(mark) {
      return;
    }

    const {value} = this.state;

    this.setState({
      currentValue: value
    });
  }

  public render () {
    const {length, mark} = this.props;
    const { currentValue } = this.state;

    return (
      <div>
        {[...Array(length)].map((v, k) => (
          <span 
            style={{cursor: mark ? "auto" : "pointer"}}
            onMouseMove={(e) => this.setCurrentValue(e, k)}
            onMouseLeave={() => this.resetCurrentValue()}
            onClick={() => this.selectValue(k)}
            key={k}
          > 
            <i className={
                k <= currentValue
                  ? "fas fa-star"
                  : "far fa-star"
            }>
            </i> 
          </span>
        ))}
      </div>
    );
  }
}