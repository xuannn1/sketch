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

    export interface User {
        id:number;
        name:string;
    }

    export namespace Home {
        export interface Thread {
            title:string;
            content:string;
            thread?:number;
            username?:string;
            create_date?:Date;
            update_date?:Date;
        }

        export function allocThread () : Thread {
            return {
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
            reply:Article.ChapterBasic;
            content:string;
            comment:{
                user:User;
                publishDate:Date;
                content:string;
            }
        }
    }

    export namespace Article {
        export interface ChapterBasic {
            id:number;    
            title:string;
        }

        export interface ChapterContent extends ChapterBasic {
            user:User;
            publishDate:Date;
            wordCounter:number;
            viewCounter:number;
            commentCounter:number;
            comments:Thread.Post[];
            content:string;
        }

        export interface Book {
            id:number;
            title:string;
            subTitle:string;
            user:User;
            publishDate:Date;
            tags:string[];
            wordCounter:number;
            viewCounter:number;
            commentCounter:number;
            downloadCounter:number;
            brief:string;
            chapters:ChapterBasic[];
            comments:Thread.Post[];
        }
    }
}







