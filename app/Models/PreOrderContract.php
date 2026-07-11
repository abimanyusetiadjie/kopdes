<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrderContract extends Model
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
        'volume_requested' => 'integer',
        'agreed_price' => 'decimal:2',
        'delivery_target_date' => 'date',
    ];

    /**
     * Get the commodity associated with this pre-order contract.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }
}
