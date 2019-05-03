import * as React from 'react';

interface Props {
  height?:number;
  style?:React.CSSProperties;
  className?:string;
  items?:JSX.Element[];
}
interface State {
}

export class FilterBar extends React.Component<Props, State> {
    public render () {
        return <div>
          <select>
            <option value="lalal">lalal</option>
            <option value="dog">dog</option>
          </select>
        </div>;
    }
}