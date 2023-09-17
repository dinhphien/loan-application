<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    public const PENDING = 'PENDING';

    public const APPROVED = 'APPROVED';

    public const PAID = 'PAID';

    protected $fillable = [
        'amount',
        'term',
        'status',
        'user_id',
    ];

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
