<?php
namespace App\Sosadfun\Traits;

trait ColumnTrait{

    protected $thread_columns = array('id', 'user_id', 'channel_id',  'title', 'brief', 'body', 'is_anonymous', 'majia', 'created_at', 'edited_at', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'use_markdown', 'use_indentation', 'view_count', 'reply_count', 'collection_count', 'download_count', 'jifen', 'weighted_jifen', 'total_char', 'responded_at', 'last_post_id',  'add_component_at', 'last_component_id', 'deleted_at', 'total_char'); // 全部的thread columns

    protected $threadbrief_columns = array('id', 'user_id', 'channel_id',  'title', 'brief',  'is_anonymous', 'majia', 'is_public', 'is_bianyuan'); // 极简版的信息

    protected $post_columns = array('id', 'type', 'user_id', 'thread_id', 'title', 'brief','body', 'is_anonymous', 'majia', 'creation_ip', 'created_at', 'edited_at', 'reply_id', 'reply_brief', 'reply_position', 'use_markdown', 'use_indentation', 'upvote_count', 'reply_count', 'view_count', 'is_folded', 'is_longpost', 'is_bianyuan', 'responded_at', 'deleted_at')  ; // 从这里排除可以不检出的column

    protected $postinfo_columns = array ('id', 'type', 'thread_id', 'title', 'brief', 'created_at', 'edited_at', 'upvote_count', 'reply_count', 'view_count', 'is_folded', 'is_bianyuan', 'responded_at');

    protected $postbrief_columns = array ('id', 'type', 'thread_id', 'title', 'brief', 'created_at', 'is_bianyuan');

}
