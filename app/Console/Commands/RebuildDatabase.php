<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Helpers\Helper;
use Cache;

class RebuildDatabase extends Command
{
    use TemporaryTraits\ModifyUserTableTraits;
    use TemporaryTraits\ModifyThreadTableTraits;
    use TemporaryTraits\ModifyRewardNVoteTableTraits;
    use TemporaryTraits\ModifyPostTableTraits;
    use TemporaryTraits\ModifyAdminRecordsTraits;
    use TemporaryTraits\ModifyQuestionBoxTraits;
    use TemporaryTraits\ModifyActivityTableTraits;
    use TemporaryTraits\ModifyReviewNCollectionTraits;
    use TemporaryTraits\ModifyQuoteNStatusTraits;
    use TemporaryTraits\CleanUpExtraThingsTraits;
    use TemporaryTraits\ShrinkColumnLengthTraits;
    use TemporaryTraits\AddTablesTraits;
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

        // $this->modifyUserTable();
        // $this->modifyThreadTable();
        // $this->modifyRewardNVoteTable();
        // $this->modifyPostTable();
        // $this->modifyAdminRecords();
        // $this->modifyQuestionBox();
        // $this->modifyActivityTable();
        // $this->modifyReviewNCollection();
        // $this->modifyQuoteNStatus();
        // $this->cleanUpExtraThings();
        // $this->shrinkColumnLength();
        // $this->addTables();

    }

}
