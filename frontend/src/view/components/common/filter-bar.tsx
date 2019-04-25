import * as React from 'react';
import { Dropdown } from './dropdown';

interface Props {
  height?:number;
  style?:React.CSSProperties;
  className?:string;
  items?:Dropdown[];
}
interface State {
}

export class FilterBar extends React.Component<Props, State> {
    public render () {
        return <div>
            
        </div>;
    }
}