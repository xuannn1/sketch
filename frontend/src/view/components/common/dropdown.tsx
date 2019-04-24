import * as React from 'react';

interface Props {
  onIndex?:number;
  title?:string;
  list:{text:string, onClick:() => void, icon?:string}[];
}
interface State {
}

export class Dropdown extends React.Component<Props, State> {
    public render () {
        return <div>
            
        </div>;
    }
}