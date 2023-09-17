<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    public const PENDING = 'PENDING';

    public const APPROVED = 'APPROVED';

    public const PAID = 'PAID';

    protected $fillable = [
        'amount',
        'deadline',
        'user_id',
        'loan_id',
        'status',
    ];
}
