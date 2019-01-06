import { Database } from './database';

export type Timestamp = string;
export type Token = string;
export type UInt = number;
export type Increments = number;

export namespace Response {
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

    export interface User {
        type:'user';
        id:number;
        attributes:Database.Users;
    }

    export interface Channel {
        type:'channel';
        id:number;
        attributes:Database.Channels;
    } 

    export interface Thread {
        type:'thread';
        id:number;
        attributes:Database.Threads;
        author:User;
        channel:Channel;
        tags:Tag[];
        recommendations?:Recommendation[];
    }

    export interface Status {
        type:'status';
        id:number;
        attributes:Database.Statuses;
        author:User;
    }

    export interface Tag {
        type:'tag';
        id:number;
        attributes:Database.Tags;
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
        attributes:Database.Posts;
        author:User;
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
        attributes:Database.Chapters & Database.Posts;
    }

    export interface Volumn {
        type:'volumn';
        id:number;
        attributes:Database.Volumns;
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
    data:T|string;
}

interface APISchema<T extends {req?:{}, res:{}}> {
    req?:T['req'];
    res:APIResponse<T['res']>;
}

export interface APIGet {
    '/':APISchema<{
        res:{
            quotes:Response.Quote[],
            recent_added_chapter_books:Response.Thread[],
            recent_responded_books:Response.Thread[],
            recent_responded_threads:Response.Thread[],
            recent_statuses:Response.Status[],
        }
    }>;
    '/config/allTags':APISchema<{
        res:{
            tags:Response.Tag[];
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
            threads:Response.Thread[],
            paginate:Response.ThreadPaginate,
        };
    }>;
    '/thread/:id':APISchema<{
        req:{
            id:number;
        };
        res:{
            thread:Response.Thread,
            posts:Response.Post[],
            paginate:Response.ThreadPaginate, 
        }
    }>;
    'book/:id':APISchema<{
        req:{
            id:number;
        };
        res:{
            thread:Response.Thread;
            chapters:Response.Chapter[];
            volumns:Response.Volumn[];
            most_upvoted:Response.Post;
            newest_comment:Response.Post;
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
            thread:Response.Thread;
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
    '/patch':APISchema<{
        req:{
            brief?:string;
            body?:string;
            is_public?:boolean;
            is_past?:boolean;
        };
        res:string; //fixme:
    }>;
}