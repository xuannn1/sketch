import { Database } from './database';

export namespace Respond {
    export interface Quote {
        type:'quote';
        id:number;
        attributes:Database.Quotes;
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
}
export interface API {
    '/':{
        get:{
            res:{
                quotes:Respond.Quote[],
                recent_added_chapter_books:Respond.Thread[],
                recent_responded_books:Respond.Thread[],
                recent_responded_threads:Respond.Thread[],
                recent_statuses:Respond.Status[],
            }
        }
    };
    'register':{
        post:{
            req:{
                name:string;
                email:string;
                password:string;
            },
        }
    };
    'login':{
        post:{
            req:{
                email:string;
                password:string;
            },
            res:string;
        }
    };
    'config/allTags':{
        get:{
            res:{
                tags:Respond.Tag[],
            }
        }
    };
    '/thread':{
        get:{
            req:{
                channel:number[],
                withBook:'book_only'|'none_book_only',
                withTag:number[],
                excludeTag:number[],
                withBianyuan:'bianyuan_only'|'none_bianyuan_only',
                ordered:'last_added_chapter_at'|'jifen'|'weighted_jifen'|'created_at'|'id'|'collections'|'total_char',
            },
            res:{
                threads:Respond.Thread[],
                paginate:Respond.ThreadPaginate,
            }
        },
        post:{
            req:{
                channel:number;
                title:string;
                brief:string;
                body:string;
            }
        }
    };
    '/thread/:id':{
        get:{
            res:{
                thread:Respond.Thread,
                posts:Respond.Post[],
                paginate:Respond.ThreadPaginate,
            }
        }
    };
    '/book/:id':{
        get:{

        }
    }
}