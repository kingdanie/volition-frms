<?php

namespace App\Console\Commands;

use App\Models\Fund;
use App\Models\FundReturn;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class AddFundReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fund-add-return';
    protected $signature = 'fund:add-return 
                            {fund_id} 
                            {frequency} 
                            {percentage} 
                            {date} 
                            {--compound=1}';



    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Add a return to a fund';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fundId = $this->argument('fund_id');
        $frequency = $this->argument('frequency');
        $percentage = (float) $this->argument('percentage');
        $date = $this->argument('date');
        $isCompound = (bool) $this->option('compound');

        // --- Validation ---
        $rules = [
            'fund_id' => 'required|integer|exists:funds,id',
            'percentage' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'frequency' => 'required|in:' . implode(',', FundReturn::VALID_FREQUENCIES),
        ];

        // --- Custom Messages ---
        $messages = [
            'frequency.in' => 'Invalid frequency. Must be monthly, quarterly, or yearly.',
            'date.date_format' => 'The date must be in YYYY-MM-DD format.',
        ];


        $validator = Validator::make([
            'fund_id' => $fundId,
            'frequency' => $frequency,
            'percentage' => $percentage,
            'date' => $date,
        ], $rules, $messages);
       

        if ($validator->fails()) {
            $this->error('Failed to add return!');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }


        // Find the fund
        $fund = Fund::findOrFail($fundId);

        // Add the return
        $return = $fund->addReturn([
            'frequency' => $frequency,
            'percentage' => $percentage,
            'date' => $date,
            'is_compound' => $isCompound,
        ]);

        $this->info("Return added successfully. Return ID: {$return->id}");
        $this->line("Fund balance updated to: {$fund->current_balance}");

        return Command::SUCCESS;
    }
}
