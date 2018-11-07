import { HomeRecommendation } from './data-types';

export type Response<DataType> = {
    code:number,
    data:DataType,
}

export interface ResponseList {
    '/homeRecommendation':Response<HomeRecommendation>,
    '/resetPwd':Response<boolean>,
    '/login':Response<boolean>,
    '/register':Response<boolean>,
}