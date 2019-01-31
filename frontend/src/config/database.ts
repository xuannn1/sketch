export namespace Database {
    export type Token = string;
    export type IPAddress = string;
    export type Address = string;
    export type UInt = number;
    export type Increments = number;
    export type Timestamp = string;

    export interface Users_Default {
        id?:Increments;
        name:string;
        email?:string;
        email_validated_at?:Timestamp;
        password?:string;
        remember_token?:Token;
        created_at?:Timestamp;
    }

    export interface User extends Users_Default {
        user_level?:number;
        last_login_ip?:IPAddress;
        last_login_at?:string;
        invitation_token_id?:number;
        brief?:string;
        sangdians?:number;
        shengfans?:number;
        xianyus?:number;
        jifens?:number;
        experience_points?:number;
        up_votes?:number;
        down_votes?:number;
        funny_votes?:number;
        fold_votes?:number;
        continued_qiandaos?:number;
        max_qiandaos?:number;
        last_qiandao_at?:string;
        unread_reminders?:number;
        unread_updates?:number;
        reviewed_public_notices?:number;
        message_limit?:number;
        no_stranger_messages?:boolean;
        no_upvote_reminders?:boolean;
        total_book_characters?:number;
        total_comment_characters?:number;
        daily_clicks?:number;
        daily_posts?:number
        daily_book_characters?:number;
        daily_comment_characters?:number;
    }

    export interface Post {
        id?:Increments;
        user_id?:Increments;
        thread_id?:Increments;
        body:string;
        preview?:string;
        is_anonymous?:boolean;
        majia?:string;
        creation_ip?:IPAddress;
        created_at?:Timestamp;
        last_edited_at?:Timestamp;
        reply_to_post_id?:UInt;
        reply_to_post_preview?:string;
        reply_position?:number;
        type?:string;
        use_markdown?:boolean;
        use_indentation?:boolean;
        up_votes?:UInt;
        down_votes?:UInt;
        fold_votes?:UInt;
        funny_votes?:UInt;
        xianyus?:UInt;
        shengfans?:UInt;
        replies?:UInt;
        is_folded?:boolean;
        allow_as_longpost?:boolean;
        is_bianyuan?:boolean;
        last_responded_at?:Timestamp;
    }

    export interface Thread {
        id?:Increments;
        user_id?:Increments;
        channel_id?:Increments;
        title:string;
        brief?:string;
        body?:string;
        last_post_id?:Increments;
        last_post_preview?:string;
        is_anonymous?:boolean;
        majia?:string|null;
        creation_ip?:IPAddress;
        created_at?:Timestamp;
        last_editor_id?:Increments;
        last_edited_at?:Timestamp;
        use_markdown?:boolean;
        use_indentation?:boolean;
        xianyus?:number;
        shengfans?:number;
        views?:number;
        replies?:number;
        collections?:number;
        downloads?:number;
        jifen?:number;
        weighted_jifen?:number;
        is_locked?:boolean;
        is_public?:boolean;
        is_bianyuan?:boolean;
        no_reply?:boolean;
        last_responded_at?:Timestamp;
        last_added_component_at?:Timestamp;
        last_component_id?:Increments;
        total_char?:UInt;
    }

    export interface Vote {
        user_id?:Increments;
        votable_type?:string;
        votable_id?:Increments;
        attitude_type?:string;
        attitude_value?:number;
        created_at?:Timestamp;
    }

    export interface Channel {
        id?:Increments;
        channel_name:string;
        channel_explanation?:string;
        order_by?:number;
        channel_rule?:string;
        is_book?:boolean;
        allow_anonymous?:boolean;
        allow_edit?:boolean;
        is_public?:boolean;
    }

    export interface Tag {
        id?:Increments;
        tag_name:string;
        tag_explanation:string|null;
        tag_type:string;
        is_bianyuan:boolean;
        is_primary:boolean;
        channel_id:UInt;
        parent_id:UInt;
        tagged_books:UInt;
    }

    export interface Chapter {
        post_id?:Increments;
        volumn_id?:Increments;
        order_ny?:number;
        title:string;
        brief?:string;
        annotation?:string;
        annotation_infront?:boolean;
        views?:UInt;
        characters?:UInt;
        previous_chapter_id?:Increments;
        next_chapter_id?:Increments;
    }

    export interface Volume {
        id?:Increments;
        title:string;
        brief:string;
        body:string;
    }

    export interface Quote {
        id?:Increments;
        body?:string;
        user_id?:Increments;
        is_anonymous?:boolean;
        majia?:string;
        not_sad?:boolean;
        is_approved?:boolean;
        reviewer_id?:Increments;
        xianyus?:number;
        created_at?:Timestamp;
    }

    export interface Title {
        id?:Increments;
        name?:string;
        description?:string;
        entitled?:UInt;
    }

    export interface User_Role {
        user_id?:Increments;
        role?:string;
        reason?:string;
        created_at?:Timestamp;
        end_at?:Timestamp;
        is_valid?:boolean;
        is_public?:boolean;
    }

    export interface Status {
        id?:Increments;
        user_id?:Increments;
        body:string;
        attachable_type?:string;
        attachable_id?:Increments;
        reply_to_status_id?:Increments;
        created_at:Timestamp;
    }
}
