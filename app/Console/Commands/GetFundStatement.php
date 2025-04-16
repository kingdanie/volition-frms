<?php

namespace App\Console\Commands;

use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetFundStatement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fund-get-statement';
    protected $signature = 'fund:get-statement {fund_id} {start_date} {end_date}';


    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Get a statement of fund returns between two dates';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fundId = $this->argument('fund_id');
        $startDate = Carbon::parse($this->argument('start_date'));
        $endDate = Carbon::parse($this->argument('end_date'));

        // Find the fund
        $fund = Fund::findOrFail($fundId);

        // Get the statement
        $statement = $fund->getStatement($startDate, $endDate);

        $this->info("Fund Statement for {$fund->name}");
        $this->line("Period: {$statement['period_start']} to {$statement['period_end']}");
        $this->line("Starting Value: {$statement['start_value']}");
        $this->line("Ending Value: {$statement['end_value']}");
        $this->line("Total Return: {$statement['total_return']} ({$statement['percentage_return']}%)");
        
        if (count($statement['returns']) > 0) {
            $this->info("\nDetailed Returns:");
            $headers = ['ID', 'Date', 'Frequency', 'Percentage', 'Compound', 'Amount'];
            $rows = [];
            
            foreach ($statement['returns'] as $return) {
                $rows[] = [
                    $return->id,
                    $return->date->toDateString(),
                    $return->frequency,
                    $return->percentage . '%',
                    $return->is_compound ? 'Yes' : 'No',
                    $return->amount
                ];
            }
            
            $this->table($headers, $rows);
        } else {
            $this->line("\nNo returns recorded in this period.");
        }
        
        return 0;
    }
}
