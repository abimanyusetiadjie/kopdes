<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraceabilityLog extends Model
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
        'harvest_date' => 'date',
        'food_safety_score' => 'integer',
    ];

    /**
     * Get the commodity associated with this traceability log.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }
}
