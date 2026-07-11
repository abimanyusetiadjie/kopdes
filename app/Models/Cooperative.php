<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cooperative extends Model
{
    use HasFactory;

    /**
     * Mass assignment protection: empty guarded array allows all fields.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get all commodities managed by this village cooperative.
     */
    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }
}
