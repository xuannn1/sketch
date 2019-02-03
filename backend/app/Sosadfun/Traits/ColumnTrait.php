<?php
namespace App\Sosadfun\Traits;

trait ColumnTrait{

    protected $thread_columns = array('id', 'user_id', 'channel_id',  'title', 'brief', 'body',  'last_post_id', 'is_anonymous', 'majia', 'created_at', 'last_edited_at', 'xianyus', 'shengfans', 'views', 'replies', 'collections', 'downloads', 'jifen', 'weighted_jifen', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'last_responded_at', 'last_added_component_at', 'last_component_id', 'deleted_at', 'total_char'); // 全部的thread columns

    protected $threadbrief_columns = array('id', 'user_id', 'channel_id',  'title',  'is_anonymous', 'majia', 'is_public', 'is_bianyuan'); // 极简版的信息

    protected $post_columns = array('id', 'type', 'user_id', 'thread_id', 'title', 'preview','body', 'is_anonymous', 'majia', 'creation_ip', 'created_at', 'last_edited_at', 'reply_to_post_id', 'reply_to_post_preview', 'reply_position', 'use_markdown', 'use_indentation', 'up_votes', 'down_votes', 'fold_votes', 'funny_votes', 'xianyus', 'shengfans', 'replies', 'is_folded', 'is_longpost', 'allow_as_longpost', 'is_bianyuan', 'last_responded_at', 'deleted_at')  ; // 从这里排除可以不检出的column

    protected $postinfo_columns = array ('id', 'type', 'thread_id', 'title', 'preview', 'created_at', 'last_edited_at', 'up_votes', 'xianyus', 'shengfans', 'replies', 'is_folded', 'is_bianyuan', 'last_responded_at');

    protected $postbrief_columns = array ('id', 'type', 'thread_id', 'title', 'preview', 'created_at', 'is_bianyuan');

}
