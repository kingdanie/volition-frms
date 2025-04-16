<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fund extends Model
{
    protected $fillable = [
        'name',
        'initial_balance',
        'current_balance',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function returns(): HasMany
    {
        return $this->hasMany(FundReturn::class);
    }

    /**
     * Add a return to the fund
     */
    public function addReturn(array $data): FundReturn
    {
        // Extract data
        $frequency = $data['frequency'];
        $percentage = $data['percentage'];
        $date = Carbon::parse($data['date']);
        $isCompound = $data['is_compound'];

        // Get fund value before this return
        $valueBefore = $this->getValueAt($date);

        // Calculate the amount based on compound or non-compound
        if ($isCompound) {
            $amount = $valueBefore * ($percentage / 100);
        } else {
            $amount = $this->initial_balance * ($percentage / 100);
        }

        $valueAfter = $valueBefore + $amount;

        // Create the return record
        $return = $this->returns()->create([
            'frequency' => $frequency,
            'is_compound' => $isCompound,
            'percentage' => $percentage,
            'date' => $date,
            'value_before' => $valueBefore,
            'value_after' => $valueAfter,
            'amount' => $amount,
        ]);

        // Update the fund's current balance
        $this->updateCurrentBalance();

        return $return;
    }

    /**
     * Revert a specific return
     */
    public function revertReturn(int $returnId): bool
    {
        $return = $this->returns()->findOrFail($returnId);

        // Mark the return as inactive
        $return->is_active = false;
        $return->save();

        // Update the current balance
        $this->updateCurrentBalance();

        return true;
    }

    /**
     * Get the fund value at a specific date
     */
    public function getValueAt(Carbon $date): float
    {
        // If the date is before the fund's start date, return initial balance
        if ($date->isBefore($this->start_date)) {
            return $this->initial_balance;
        }

        // Get all active returns up to the given date, ordered by date
        $returns = $this->returns()
            ->where('date', '<=', $date)
            ->where('is_active', true)
            ->orderBy('date')
            ->get();

        if ($returns->isEmpty()) {
            return $this->initial_balance;
        }

        // Start with the initial balance
        $value = $this->initial_balance;
        
        // Apply each return in chronological order
        foreach ($returns as $return) {
            if ($return->is_compound) {
                $value += $return->amount;
            } else {
                // For non-compound, we add the fixed amount
                $value = $return->value_after;
            }
        }

        return $value;
    }

    /**
     * Update the current_balance based on all active returns
     */
    public function updateCurrentBalance(): void
    {
        $this->current_balance = $this->getValueAt(Carbon::now());
        $this->save();
    }

    /**
     * Get a statement of fund returns between dates
     */
    public function getStatement(Carbon $startDate, Carbon $endDate): array
    {
        $returns = $this->returns()
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('is_active', true)
            ->orderBy('date')
            ->get();

        $startValue = $this->getValueAt($startDate);
        $endValue = $this->getValueAt($endDate);
        $totalReturn = $endValue - $startValue;
        $percentageReturn = $startValue > 0 ? ($totalReturn / $startValue * 100) : 0;

        return [
            'fund_id' => $this->id,
            'fund_name' => $this->name,
            'period_start' => $startDate->toDateString(),
            'period_end' => $endDate->toDateString(),
            'start_value' => $startValue,
            'end_value' => $endValue,
            'total_return' => $totalReturn,
            'percentage_return' => $percentageReturn,
            'returns' => $returns,
        ];
    }
}
