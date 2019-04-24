import * as React from 'react';

interface Props {
  length:number;
  mark?:number;
  onClick:(mark:number) => void;
}
interface State {
}

export class Mark extends React.Component<Props, State> {
    public render () {
        return <div>
            
        </div>;
    }
}