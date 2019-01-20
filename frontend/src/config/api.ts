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
            xianyus?:number;
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
        attributes:Database.User;
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
    }

    export function allocThread () : Thread {
        return {
            type: 'thread',
            id: 0,
            attributes: {
                title: '',
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

    export interface Post {
        type:'post';
        id:number;
        attributes:Database.Post;
        author:User;
    }

    export function allocPost () : Post {
        return {
            type: 'post',
            id: 0,
            attributes: {
                body: '',
            },
            author: allocUser(),
        };
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
        attributes:Database.Chapter & Database.Post;
    }

    export interface Volumn {
        type:'volumn';
        id:number;
        attributes:Database.Volume;
    }
}

export namespace Request {
    export namespace Thread {
        export type withBook = 'book_only'|'none_book_only';
        export type withBianyuan = 'bianyuan_only'|'none_bianyuan_only';
        export type ordered = 'last_added_chapter_at'|'jifen'|'weighted_jifen'|'created_at'|'id'|'collections'|'total_char';
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
    '/thread':APISchema<{
        req:{
            channel:number[],
            withBook:Request.Thread.withBook,
            withTag:number[],
            excludeTag:number[],
            withBianyuan:Request.Thread.withBianyuan,
            ordered:Request.Thread.ordered,
        };
        res:{
            threads:ResData.Thread[],
            paginate:ResData.ThreadPaginate,
        };
    }>;
    '/thread/:id':APISchema<{
        req:{
            id:number;
        };
        res:{
            thread:ResData.Thread,
            posts:ResData.Post[],
            paginate:ResData.ThreadPaginate, 
        }
    }>;
    '/book/:id':APISchema<{
        req:{
            id:number;
        };
        res:{
            thread:ResData.Thread;
            chapters:ResData.Chapter[];
            volumns:ResData.Volumn[];
            most_upvoted:ResData.Post;
            newest_comment:ResData.Post;
        }
    }>
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
            channel:number;
            title:string;
            brief:string;
            body:string; 
        };
        res:{
            thread:ResData.Thread;
        }
    }>;
    '/recommendation':APISchema<{
        req:{
            thread:number;
            brief:string;
            type:'short'|'long'|'topic';
            body?:string;
            users:number[];
        };
        res:string;
    }>;
}

export interface APIPatch {
    '/recommendation':APISchema<{
        req:{
            brief?:string;
            body?:string;
            is_public?:boolean;
            is_past?:boolean;
        };
        res:string; //fixme:
    }>;
}