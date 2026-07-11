<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    use HasFactory;

    /**
     * Mass assignment protection.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Type casting for attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'current_capacity' => 'integer',
        'base_price_b2b' => 'decimal:2',
    ];

    /**
     * Get the cooperative that owns the commodity.
     */
    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Get all pre-order contracts for this commodity.
     */
    public function preOrderContracts(): HasMany
    {
        return $this->hasMany(PreOrderContract::class);
    }

    /**
     * Get all traceability logs for this commodity.
     */
    public function traceabilityLogs(): HasMany
    {
        return $this->hasMany(TraceabilityLog::class);
    }
}
