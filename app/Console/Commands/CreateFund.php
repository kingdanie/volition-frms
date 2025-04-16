<?php

namespace App\Console\Commands;

use App\Models\Fund;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class CreateFund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:create
                            {name : The name of the new fund}
                            {initial_balance : The starting balance of the fund} 
                            {start_date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new investment fund';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $initialBalance = (float) $this->argument('initial_balance');
        $startDate = $this->argument('start_date') ? Carbon::parse($this->argument('start_date')) : Carbon::now();

        

        // --- Validation ---
        $rules = [
            'name' => 'required|string|max:255|unique:funds,name',
            'balance' => 'required|numeric|min:0', // Ensure balance is numeric and non-negative
        ];

        // --- Custom Messages ---
        $messages = [
            'balance' => 'Initial balance must be greater than zero.',
        ];

        $validator = Validator::make([
            'name' => $name,
            'balance' => $initialBalance,
        ], $rules, $messages);


        if ($validator->fails()) {
            $this->error('Fund creation failed!');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        try {
            $fund = Fund::create([
                'name' => $name,
                'initial_balance' => $initialBalance,
                'current_balance' => $initialBalance,
                'start_date' => $startDate,
            ]);

            $this->info("Fund '{$fund->name}' created successfully with ID: {$fund->id} and starting balance: {$fund->initial_balance} Start Date: {$fund->start_date->toDateString()}");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
