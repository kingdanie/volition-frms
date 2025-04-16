<?php

namespace App\Console\Commands;

use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;


class GetFundValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fund-get-value';
    protected $signature = 'fund:get-value {fund_id} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Get the calculated value of a fund at a specific date';


    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fundId = $this->argument('fund_id');
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::now();

         // --- Validation ---
         $validator = Validator::make([
            'fund_id' => $fundId,
            'date' => $date,
        ], [
            'fund_id' => 'required|integer|exists:funds,id', // Check if fund exists
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error('Failed to get fund value!');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        // Find the fund
        $fund = Fund::findOrFail($fundId);

        // Get the value
        $value = $fund->getValueAt($date);

        $this->info("Fund value on {$date->toDateString()}: {$value}");
        
        return Command::SUCCESS;
    }
}
