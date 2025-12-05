<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditsTopup extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'plan_id', 'credits', 'amount', 'order_id',
        'cf_order_id', 'payment_status', 'cf_payment_response'
    ];
}
