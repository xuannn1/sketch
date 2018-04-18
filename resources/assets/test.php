<?php
\DB::table('users')->where('deleted_at','=',null)->update(['users.unread_reminders' => \DB::raw("message_reminders + post_reminders + reply_reminders + postcomment_reminders + upvote_reminders")]);
