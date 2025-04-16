<?php

namespace App\Console\Commands;

use App\Models\Fund;
use App\Models\FundReturn;
use Illuminate\Console\Command;

class RevertFundReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fund-revert-return';
    protected $signature = 'fund:revert-return {return_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Revert a return from a fund';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $returnId = $this->argument('return_id');

        // Find the return
        $return = FundReturn::findOrFail($returnId);
        $fund = $return->fund;

        // Revert the return
        $fund->revertReturn($returnId);

        $this->info("Return reverted successfully.");
        $this->line("Fund balance updated to: {$fund->current_balance}");
        
        return 0;
    }
}
