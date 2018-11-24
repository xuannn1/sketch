import { HomeDefaultData } from '../view/mobile/home/default';

export type Response<DataType> = {
    code:number,
    data:DataType,
}

export interface ResponseList {
    '/home':Response<HomeDefaultData>,
    '/resetPwd':Response<boolean>,
    '/login':Response<boolean>,
    '/register':Response<boolean>,
}