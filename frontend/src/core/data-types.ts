export interface Quote {
    id:number;
    quote:string;
    anonymous:number;
    majia?:string;
    notsad:number;
    approved:number;
    reviewed:number;
    xianyu:number;
    create_at?:Date;
    update_at?:Date;
    user_name:string;
}