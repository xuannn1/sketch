import { DataType } from './data-types';

export type Response<DataType> = {
    code:number,
    data:DataType,
}

export interface ResponseList {
    '/homeRecommendation':Response<DataType.Home.RecommendationCard>,
    '/homeThread':Response<DataType.Home.ThreadCard>,
    '/resetPwd':Response<boolean>,
    '/login':Response<boolean>,
    '/register':Response<boolean>,
}