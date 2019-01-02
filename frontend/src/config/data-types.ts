export namespace DataType {
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

    export interface Tag {
        id:number;
        name:string;
    }

    export interface User {
        id:number;
        name:string;
    }
    function allocUser () {
        return {
            id: 0,
            name: ''
        }
    }

    export namespace Home {
        export interface Thread {
            id:number;
            title:string;
            content:string;
            thread?:number;
            username?:string;
            create_date?:Date;
            update_date?:Date;
        }

        export function allocThread () : Thread {
            return {
                id: 0,
                title: '',
                content: '',
            };
        }
    
        export interface Recommendation extends Thread {
            recommendation?:number;
        }

        export function allocRecommendation () : Recommendation {
            return allocThread();
        }
    }

    export namespace Thread {
        export interface Post {
            id:number;
            user:User;
            publishDate:Date;
            reply:Book.ChapterTitle;
            content:string;
            comment:{
                user:User;
                publishDate:Date;
                content:string;
            }
        }
    }

    export namespace Book {
        export interface ChapterTitle {
            id:number;    
            title:string;
        }

        export interface ChapterContent extends ChapterTitle {
            user:User;
            publishDate:Date;
            wordCounter:number;
            viewCounter:number;
            commentCounter:number;
            comments:Thread.Post[];
            content:string;
        }

        export interface Profile {
            id:number;
            title:string;
            subTitle:string;
            user:User;
            publishDate:Date;
            updateDate:Date;
            tags:Tag[];
            wordCounter:number;
            viewCounter:number;
            commentCounter:number;
            downloadCounter:number;
            brief:string;
            comments:Thread.Post[];
            threadId:number;
        }

        export function allocProfile () : Profile {
            return {
                id: 0,
                title: '',
                subTitle: '',
                user: allocUser(),
                publishDate: new Date(),
                updateDate: new Date(),
                tags: [],
                wordCounter: 0,
                viewCounter: 0,
                commentCounter: 0,
                downloadCounter: 0,
                brief: '',
                comments: [],
                threadId:0,
            }
        }
    }
}







