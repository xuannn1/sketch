import { HomeMainData } from '../view/mobile/home/main';
import { HomeBookData } from '../view/mobile/home/books';

export type Response<DataType> = {
    code:number,
    data:DataType,
}

export interface ResponseList {
    '/resetPwd':Response<boolean>,
    '/login':Response<boolean>,
    '/register':Response<boolean>,
    '/home':Response<HomeMainData>,
    '/books':Response<HomeBookData>,
}