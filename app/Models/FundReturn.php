<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundReturn extends Model
{
    protected $fillable = [
        'fund_id',
        'frequency',
        'is_compound',
        'percentage',
        'date',
        'value_before',
        'value_after',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_compound' => 'boolean',
        'is_active' => 'boolean',
        'percentage' => 'float',
    ];

    /**
     * The valid frequency values.
     *
     * @var array<string>
     */
    public const VALID_FREQUENCIES = ['monthly', 'quarterly', 'yearly'];


    /**
     * Get the fund that this return belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Fund, \App\Models\FundReturn>
     */
    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class);
    }
}
