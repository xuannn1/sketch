<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Helpers\Helper;
use Cache;
use App\Console\Commands\TemporaryTraits\ModifyUserTableTraits;
use App\Console\Commands\TemporaryTraits\ModifyThreadTableTraits;
use App\Console\Commands\TemporaryTraits\ModifyRewardNVoteTableTraits;
use App\Console\Commands\TemporaryTraits\ModifyPostTableTraits;
use App\Console\Commands\TemporaryTraits\ModifyAdminRecordsTraits;
use App\Console\Commands\TemporaryTraits\ModifyQuestionBoxTraits;
use App\Console\Commands\TemporaryTraits\ModifyActivityTableTraits;
use App\Console\Commands\TemporaryTraits\ModifyReviewNCollectionTraits;
use App\Console\Commands\TemporaryTraits\ModifyQuoteNStatusTraits;
use App\Console\Commands\TemporaryTraits\CleanUpExtraThingsTraits;



class RebuildDatabase extends Command
{
    use ModifyUserTableTraits;
    use ModifyThreadTableTraits;
    use ModifyRewardNVoteTableTraits;
    use ModifyPostTableTraits;
    use ModifyAdminRecordsTraits;
    use ModifyQuestionBoxTraits;
    use ModifyActivityTableTraits;
    use ModifyReviewNCollectionTraits;
    use ModifyQuoteNStatusTraits;
    use CleanUpExtraThingsTraits;

    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'rebuild:database';
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'update database tables so it fits with new ER-sosad form';
    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {

        // $this->modifyUserTable();//task 1
        // $this->modifyThreadTable(); // task 2
        // $this->modifyRewardNVoteTable(); // task 3
        // $this->modifyPostTable(); // task 4
        // $this->modifyAdminRecords();
        // $this->modifyQuestionBox();
        // $this->modifyActivityTable();
        // $this->modifyReviewNCollection();
        // $this->modifyQuoteNStatus();
        $this->cleanUpExtraThings();

    }

}
