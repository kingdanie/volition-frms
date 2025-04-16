<?php

namespace Database\Seeders;

use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few sample funds
        $funds = [
            [
                'name' => 'Growth Fund',
                'initial_balance' => 100000.00,
                'current_balance' => 100000.00,
                'start_date' => Carbon::parse('2023-01-01'),
                'returns' => [
                    [
                        'frequency' => 'monthly',
                        'is_compound' => true,
                        'percentage' => 1.5,
                        'date' => '2023-01-31',
                    ],
                    [
                        'frequency' => 'monthly',
                        'is_compound' => true,
                        'percentage' => 0.8,
                        'date' => '2023-02-28',
                    ],
                    [
                        'frequency' => 'quarterly',
                        'is_compound' => false,
                        'percentage' => 3.0,
                        'date' => '2023-03-31',
                    ],
                ]
            ],
            [
                'name' => 'Conservative Fund',
                'initial_balance' => 250000.00,
                'current_balance' => 250000.00,
                'start_date' => Carbon::parse('2023-01-01'),
                'returns' => [
                    [
                        'frequency' => 'monthly',
                        'is_compound' => true,
                        'percentage' => 0.5,
                        'date' => '2023-01-31',
                    ],
                    [
                        'frequency' => 'monthly',
                        'is_compound' => true,
                        'percentage' => 0.4,
                        'date' => '2023-02-28',
                    ],
                    [
                        'frequency' => 'quarterly',
                        'is_compound' => true,
                        'percentage' => 1.2,
                        'date' => '2023-03-31',
                    ],
                ]
            ],
            [
                'name' => 'Aggressive Fund',
                'initial_balance' => 50000.00,
                'current_balance' => 50000.00,
                'start_date' => Carbon::parse('2023-01-01'),
                'returns' => [
                    [
                        'frequency' => 'monthly',
                        'is_compound' => true,
                        'percentage' => 2.3,
                        'date' => '2023-01-31',
                    ],
                    [
                        'frequency' => 'monthly',
                        'is_compound' => true,
                        'percentage' => -1.2,
                        'date' => '2023-02-28',
                    ],
                    [
                        'frequency' => 'quarterly',
                        'is_compound' => true,
                        'percentage' => 4.5,
                        'date' => '2023-03-31',
                    ],
                ]
            ]
        ];

        foreach ($funds as $fundData) {
            $returns = $fundData['returns'];
            unset($fundData['returns']);

            // Create the fund
            $fund = Fund::create($fundData);

            // Add returns to the fund
            foreach ($returns as $returnData) {
                $fund->addReturn($returnData);
            }

            // Update the fund's current balance
            $fund->updateCurrentBalance();
        }
    }
}
