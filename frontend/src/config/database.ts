export namespace Database {
  export type Token = string;
  export type IPAddress = string;
  export type Address = string;
  export type UInt = number;
  export type Increments = number;
  export type Timestamp = string;

  export interface User_Default {
    id?:Increments;
    name:string;
    email?:string;
    email_validated_at?:Timestamp;
    password?:string;
    remember_token?:Token;
    created_at?:Timestamp;
    deleted_at?:Timestamp;
    title_id?:Increments;
  }

  export interface User_Info {
    user_id?:Increments;
    user_level?:number;
    last_login_ip?:IPAddress;
    last_login_at?:string;
    invitation_token_id?:number;
    brief?:string;
    sangdian?:number;
    shengfan?:number;
    xianyu?:number;
    jifen?:number;
    exp?:number;
    upvote_count?:number;
    downvote_count?:number;
    funnyvote_count?:number;
    foldvote_count?:number;
    continued_qiandao?:number;
    max_qiandao?:number;
    last_qiandao_at?:string;
    // unread_reminders?:number;
    // unread_updates?:number;
    reviewed_public_notices?:number;
    message_limit?:number;
    no_stranger_message?:boolean;
    no_upvote_reminder?:boolean;
    total_book_characters?:number;
    total_comment_characters?:number;
    total_clicks?:number;
    daily_clicks?:number;
    // daily_posts?:number;
    // daily_book_characters?:number;
    // daily_comment_characters?:number;
  }
  export interface User_Profile {
    user_id?:Increments;
    description?:string;
    use_markdown?:boolean;
    use_indentation?:boolean;
  }

  export interface Post {
    id?:Increments;
    post_type?:string;
    user_id?:Increments;
    thread_id?:Increments;
    title?:string;
    brief?:string;
    body?:string;
    is_anonymous?:boolean;
    majia?:string;
    creation_ip?:IPAddress;
    created_at?:Timestamp;
    edited_at?:Timestamp;
    reply_id?:UInt;
    reply_brief?:string;
    reply_position?:number;
    is_folded?:boolean;
    is_bianyuan?:boolean;
    use_markdown?:boolean;
    use_indentation?:boolean;
    upvote_count?:UInt;
    reply_count?:UInt;
    view_count?:UInt;
    char_count?:UInt;
    responded_at?:Timestamp;
    deleted_at?:Timestamp;
  }

  export interface Thread {
    id?:Increments;
    user_id?:Increments;
    channel_id:Increments;
    title:string;
    brief?:string;
    body?:string;
    is_anonymous?:boolean;
    majia?:string|null;
    creation_ip?:IPAddress;
    created_at?:Timestamp;
    edited_at?:Timestamp;
    is_locked?:boolean;
    is_public?:boolean;
    is_bianyuan?:boolean;
    no_reply?:boolean;
    use_markdown?:boolean;
    use_indentation?:boolean;
    view_count?:number;
    reply_count?:number;
    collection_count?:number;
    download_count?:number;
    jifen?:number;
    weighted_jifen?:number;
    total_char?:UInt;
    responded_at?:Timestamp;
    last_post_id?:Increments;
    added_component_at?:Timestamp;
    last_component_id?:Increments;
    deleted_at?:Timestamp;
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
    channel_type:string;
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
    book_count:UInt;
  }

  export interface Chapter {
    id?:Increments;
    volumn_id?:Increments;
    order_by?:number;
    warning?:string;
    annotation?:string;
    previous_id?:Increments;
    next_id?:Increments;
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
    xianyu?:number;
    created_at?:Timestamp;
  }

  export interface Title {
    id?:Increments;
    name?:string;
    description?:string;
    user_count?:UInt;
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
    brief?:string;
    body:string;
    attachable_type?:string;
    attachable_id?:Increments;
    reply_id?:Increments;
    no_reply?:boolean;
    reply_count?:UInt;
    forward_count?:UInt;
    upvote_count?:UInt;
    created_at:Timestamp;
  }

  export interface Collection {
    id?:Increments;
    user_id?:Increments;
    thread_id?:Increments;
    keep_updated?:boolean;
    is_updated?:boolean;
  }

  export interface Review {
    id?:Increments;
    thread_id?:UInt;
    recommend?:boolean;
    long?:boolean;
    author_disapprove?:boolean;
    editor_recommend?:boolean;
    rating?:UInt;
    redirect_count?:UInt;

  }
}
