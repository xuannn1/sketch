<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait RenameFinalTableTraits{

    public function renameFinalTable()
    {
        $this->renameUsersTable();
        $this->renameUserInfosTable();
        $this->renamePostsTable();
        $this->renameThreadsTable();
    }

    public function renameUsersTable()
    {
        Schema::table('users', function($table){
            $table->renameColumn('indentation', 'use_indentation');
            echo "echo updated users table.\n";
        });
    }

    public function renameUserInfosTable()
    {
        Schema::table('user_infos', function($table){
            $table->renameColumn('clicks', 'total_clicks');
            $table->renameColumn('public_notices', 'reviewed_public_notices');
            $table->renameColumn('views', 'view_counts');
            echo "echo updated user_infos table.\n";
        });
    }

    public function renamePostsTable()
    {
        Schema::table('posts', function($table){
            $table->renameColumn('funny', 'funnyvote_count');
            $table->renameColumn('fold', 'foldvote_count');
            $table->renameColumn('bianyuan', 'is_bianyuan');
            $table->renameColumn('anonymous', 'is_anonymous');
            $table->renameColumn('markdown', 'use_markdown');
            $table->renameColumn('indentation', 'use_indentation');
            echo "echo updated posts table.\n";
        });

    }

    public function renameThreadsTable()
    {
        Schema::table('threads', function($table){
            $table->renameColumn('locked', 'is_locked');
            $table->renameColumn('public', 'is_public');
            $table->renameColumn('bianyuan', 'is_bianyuan');
            $table->renameColumn('anonymous', 'is_anonymous');
            $table->renameColumn('markdown', 'use_markdown');
            $table->renameColumn('indentation', 'use_indentation');
            echo "echo updated threads table.\n";
        });

    }
}
