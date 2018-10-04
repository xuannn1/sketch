import * as React from 'react';

interface Quote {
    quote:string;
    anonymous:boolean;
    majia:string;
    name:string;
    xianyu:number;
}

interface Props {
    quotes:Quote[];
}

interface State {

}

export class Banner extends React.Component<Props, State> {

}