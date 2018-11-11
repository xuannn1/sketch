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

    export namespace Home {
        export interface Thread {
            title:string;
            content:string;
            thread?:number;
            username?:string;
            create_date?:Date;
            update_date?:Date;
        }
    
        export interface Recommendation extends Thread {
            recommendation?:number;
        }
    
        export interface RecommendationCard {
            cards:Recommendation[];
            long:Recommendation;
        }
        
        export interface ThreadCard {
            latest:Thread[];
            best:Thread[];
        }
    }
}







