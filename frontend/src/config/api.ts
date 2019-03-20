import { Database } from './database';

export type Timestamp = string;
export type Token = string;
export type UInt = number;
export type Increments = number;

export namespace ResData {
    export interface Quote {
        type:'quote';
        id:number;
        attributes:{
            body:string;
            user_id?:Increments;
            is_anonymous?:boolean;
            majia?:string;
            not_sad?:boolean;
            is_approved?:boolean;
            reviewer_id?:Increments;
            xianyu?:number;
            created_at?:Timestamp;
        };
        author:User;
    }

    export function allocQuote () {
        return {
            type: 'quote',
            id: 0,
            attributes: {
                body: '',
            },
            author: allocUser(),
        }
    }

    export interface User {
        type:'user';
        id:number;
        attributes:Database.User_Default;
        followInfo?:{
            keep_updated:boolean;
            is_updated:boolean;
        }
    }

    export function allocUser () : User {
        return {
            type: 'user',
            id: 0,
            attributes: {
                name: '',
            },
        };
    }

    export interface Channel {
        type:'channel';
        id:number;
        attributes:Database.Channel;
    }

    export interface Thread {
        type:'thread';
        id:number;
        attributes:Database.Thread;
        author:User;
        channel?:Channel;
        tags?:Tag[];
        recommendations?:Recommendation[];
        last_component?:Post;
        last_post?:Post;
    }

    export function allocThread () : Thread {
        return {
            type: 'thread',
            id: 0,
            attributes: {
                title: '',
                channel_id: 0,
            },
            author: allocUser(),
        };
    }

    export interface Status {
        type:'status';
        id:number;
        attributes:Database.Status;
        author:User;
    }

    export interface Tag {
        type:'tag';
        id:number;
        attributes:Database.Tag;
    }

    export interface ThreadPaginate {
        total:number;
        count:number;
        per_page:number;
        current_page:number;
        total_pages:number;
    }

    export function allocThreadPaginate () : ThreadPaginate {
        return {
            total: 1,
            count: 1,
            per_page: 1,
            current_page: 1,
            total_pages: 1,
        };
    }

    export interface Post {
        type:'post';
        id:number;
        attributes:Database.Post;
        author?:User;
        review?:Review;
        answer?:Thread;
        chapter?:Chapter;
    }

    export function allocPost () : Post {
        return {
            type: 'post',
            id: 0,
            attributes: {
                body: '',
            },
        };
    }

    export interface Review {
        type:'review',
        id:number;
        attributes:{},
        reviewee:Database.Thread,
    }

    export function allocReview () {
        return {
            type: 'review',
            id: 0,
            attributes: {},
            reviewee: allocThread(),
        }
    }

    export interface Recommendation {
        type:'recommendation';
        id:number;
        attributes:{
            brief:string;
            body:string;
            type:'long'|'shot';
            created_at:Database.Timestamp;
        }
        authors:User[];
    }

    export interface Chapter {
        type:'chapter';
        id:number;
        attributes:Database.Chapter;
    }

    export function allocChapter () : Chapter {
        return {
            type: 'chapter',
            id: 0,
            attributes: {

            },
        };
    }

    export interface Volumn {
        type:'volumn';
        id:number;
        attributes:Database.Volume;
    }

    export interface Date {
        date:Timestamp;
        timezone_type:number;
        timezone:string;
    }

    export interface Message {
        type:'message';
        id:number;
        attributes:{
            poster_id:number;
            receiver_id:number;
            message_body:string;
            created_at:Timestamp;
            seen:boolean;
        };
        poster:User;
        receiver:User;
    }

    export function allocMessage () : Message {
        return {
            type: 'message',
            id: 0,
            attributes: {
                poster_id: 0,
                receiver_id: 0,
                message_body: '',
                created_at: '',
                seen: false,
            },
            poster: allocUser(),
            receiver: allocUser(),
        };
    }
}

export namespace ReqData {
    export namespace Thread {
        export enum ordered {
            latest_added_component = 'latest_added_component', //按最新更新时间排序
            jifen = 'jifen',  //按总积分排序
            weighted_jifen = 'weighted_jifen', //按平衡积分排序
            latest_created = 'latest_created', //按创建时间排序
            id = 'id',  //按id排序
            collection_count = 'collection_count', //按收藏总数排序
            total_char = 'total_char', //按总字数排序
        }
        // （是否仅返回边缘/非边缘内容）
        export enum withBianyuan {
            bianyuan_only = 'bianyuan_only',
            none_bianyuan_only = 'none_bianyuan_only',
        }

        export enum withType {
            thread = 'thread',
            book = 'book',
            list = 'list', //收藏单
            column = 'column',
            request = 'request',
            homework = 'homework',
        }
    }

    export namespace Message {
        export enum style {
            sendbox,
            receiveBox,
            dialogue,
        }

        export enum ordered {
            oldest,
            latest
        }

        export enum read {
            read_only,
            unread_only,
        }
    }

    export namespace Collection {
        export enum Type {
            thread = 'thread',
            book = 'book',
            list = 'list',
            request = 'request',
            homework = 'homework',
        }
    }
}


interface APIResponse<T> {
    code:number;
    data:T;
}

interface APISchema<T extends {req:{}|undefined, res:{}}> {
    req?:T['req'];
    res:APIResponse<T['res']>;
}

export interface APIGet {
    '/':APISchema<{
        req:undefined;
        res:{
            quotes:ResData.Quote[],
            recent_added_chapter_books:ResData.Thread[],
            recent_responded_books:ResData.Thread[],
            recent_responded_threads:ResData.Thread[],
            recent_statuses:ResData.Status[],
        }
    }>;
    '/config/allTags':APISchema<{
        req:undefined;
        res:{
            tags:ResData.Tag[];
        }
    }>;
    '/config/noTongrenTags':APISchema<{
        req:undefined;
        res:{
            tags:ResData.Tag[];
        }
    }>;
    '/thread':APISchema<{
        req:{
            channels?:number[],
            tags?:number[],
            excludeTag?:number[],
            withBianyuan?:ReqData.Thread.withBianyuan,
            ordered?:ReqData.Thread.ordered,
            withType?:ReqData.Thread.withType,
            page?:number;
        };
        res:{
            threads:ResData.Thread[],
            paginate:ResData.ThreadPaginate,
        };
    }>;
    '/homethread':APISchema<{
        req:undefined;
        res:{
            [idx:string]:{
                channel:ResData.Channel;
                threads:ResData.Thread[];
            }
        };
    }>;
    '/thread/:id':APISchema<{
        req:{
            id:number;
            page?:number;
        };
        res:{
            thread:ResData.Thread,
            posts:ResData.Post[],
            paginate:ResData.ThreadPaginate,
        }
    }>;
    '/homebook':APISchema<{
        req:undefined;
        res:{
            recent_long_recommendations:ResData.Post[];
            recent_short_recommendations:ResData.Post[];
            random_short_recommendations:ResData.Post[];
            recent_custom_short_recommendations:ResData.Post[];
            recent_custom_long_recommendations:ResData.Post[];
            recent_added_chapter_books:ResData.Thread[];
            recent_responded_books:ResData.Thread[];
            highest_jifen_books:ResData.Thread[];
            most_collected_books:ResData.Thread[];
        };
    }>;
    '/book/:id':APISchema<{
        req:{
            id:number;
            page?:number;
        };
        res:{
            thread:ResData.Thread;
            chapters:ResData.Post[];
            volumns:ResData.Volumn[];
            paginate:ResData.ThreadPaginate,
            most_upvoted:ResData.Post;
            top_review:ResData.Post|null;
        }
    }>;
    '/collection':APISchema<{
        req:{
            user_id?:number;
            withType?:ReqData.Collection.Type;
            ordered?:ReqData.Thread.ordered;
        };
        res:{
            threads:ResData.Thread[];
            paginate:ResData.ThreadPaginate;
        };
    }>;
    '/user/:id/message':APISchema<{
        req:{
            id:number;
            withStyle:ReqData.Message.style;
            chatWith?:Increments;
            ordered?:ReqData.Message.ordered;
            read?:ReqData.Message.read;
        };
        res:{
            messages:ResData.Message[];
            paginate:ResData.ThreadPaginate;
            style:ReqData.Message.style;
        };
    }>;
    '/status':APISchema<{
        // fixme:
        req:{

        };
        res:{

        };
    }>;
}
export interface APIPost {
    '/register':APISchema<{
        req:{
            name:string;
            password:string;
            email:string;
        };
        res:{
            token:string;
            name:string;
        };
    }>;
    '/login':APISchema<{
        req:{
            email:string;
            password:string;
        };
        res:{
            token:string;
        };
    }>;
    '/thread':APISchema<{
        req:{
            title:string;
            brief:string;
            body:string;
            no_reply?:boolean;
            use_markdown?:boolean;
            use_indentation?:boolean;
            is_bianyuan?:boolean;
            is_not_public?:boolean;
        };
        res:{
            thread:ResData.Thread;
        }
    }>;
    '/thread/:id/synctags':APISchema<{
        req:{
            id:number;
            tags:number[];
        };
        res:{};
    }>;
    '/thread/:id/post':APISchema<{
        req:{
            id:number;
            body:string;
            brief:string;
            is_anonymous?:boolean;
            majia?:string;
            reply_id?:number;
            use_markdown?:boolean;
            use_indentation?:boolean;
            is_bianyuan?:boolean;
        };
        res:{
            body:string;
            brief:string;
            thread_id:number;
            is_anonymous:boolean;
            use_markdown:boolean;
            use_indentation:boolean;
            is_bianyuan:boolean;
            last_responed_at:ResData.Date;
            user_id:number;
            type:'post';
            created_at:Timestamp;
            id:number;
        };
    }>;
    '/thread/:id/chapter':APISchema<{
        req:{
            id:number;
            title:string;
            brief:string;
            body:string;
            annotation?:string;
            annotation_infront?:boolean;
        };
        res:ResData.Chapter[];
    }>;
    '/recommendation':APISchema<{
        req:{
            type:'short'|'long'|'topic';
            body?:string;
            users:number[];
        };
        res:string;
    }>;
    '/quote':APISchema<{
        req:{
            body:string;
            is_anonymous?:boolean;
            majia?:string;
        };
        res:{
            body:string;
            user_id?:number;
            is_anonymous?:boolean;
            majia?:string;
            created_at?:ResData.Date;
            id?:number;
        };
    }>;
}

export interface APIPatch {
    '/recommendation':APISchema<{
        req:{
            is_public?:boolean;
            is_past?:boolean;
        };
        res:string; //fixme:
    }>;
    '/thread/:id':APISchema<{
        req:{
            id:number;
            title?:string;
            brief?:string;
            body?:string;
            no_reply?:boolean;
            use_markdown?:boolean;
            use_indentation?:boolean;
            is_bianyuan?:boolean;
            is_not_public?:boolean;
        };
        res:{}; //fixme:
    }>;
    '/thread/:tid/post/:pid':APISchema<{
        req:{
            tid:number;
            pid:number;
            body?:string;
            brief?:string;
            is_anonymous?:boolean;
            use_markdown?:boolean;
            use_indentation?:boolean;
        };
        res:{}; //fixme:
    }>;
}

export interface APIPut {
    '/thread/:tid/chapter/:cid':APISchema<{
        req:{
            tid:number;
            cid:number;
            title?:string;
            brief?:string;
            body?:string;
            annotation?:string;
            annotation_infront?:boolean;
        };
        res:{}; //fixme:
    }>;
}
